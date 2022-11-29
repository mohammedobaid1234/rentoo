<?php

namespace Modules\Vendors\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TimesLabelController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " انواع التوقيت ";
    private $model = \Modules\Vendors\Entities\TimeLabel::class;
    
    public function manage(){
        $this->can('vendors_module_times_label_manage', 'view');
        $data['activePage'] = ['vendors' => 'times_label'];
        $data['breadcrumb'] = [
            ['title' => "التوقيتات"],
            ['title' => $this->title]
        ];

        return view("vendors::times_label", $data);
    }

    public function datatable(Request $request){
        $this->can('vendors_module_times_label_manage');

        $eloquent = $this->model::with(['times.type_of_vendor']);
        // return $eloquent->get();
        $filters = [];
        $columns = [
            ['title' => 'الاسم بالعربي', 'column' => 'name_ar' , 'formatter' => 'name_ar' ],
            ['title' => 'الاسم بالانجليزي', 'column' => 'name_en', 'formatter' => 'name_en' ],
            ['title' => 'انواع الموردين', 'column' => 'type_of_vendor.name', 'merge' => true, 'formatter' => 'type_of_vendor'],

            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']

        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function store(Request $request){
        \Auth::user()->authorize('vendors_module_times_label_store');

        $request->validate([
            'label_ar' => 'required',
            'label_en' => 'required',
            'types_of_vendor' => 'required',
        ]);
        \DB::beginTransaction();
        try {
           $times_label = new \Modules\Vendors\Entities\TimeLabel();
           $times_label
            ->setTranslation('label', 'en',  $request->label_en)
            ->setTranslation('label', 'ar',   $request->label_ar);
            $times_label->save();
            foreach (explode(',',$request->types_of_vendor) as $type_of_vendor) {
                $type = new \Modules\Vendors\Entities\TimeTypeOfVendor();
                $type->time_id = $times_label->id;
                $type->type_of_vendor = trim($type_of_vendor);
                $type->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('vendors_module_times_label_update');
        // dd($request->types_of_vendor);
        $request->validate([
            'label_ar' => 'required',
            'label_en' => 'required',
        ]);

        \DB::beginTransaction();
        try {

            $times_label =  \Modules\Vendors\Entities\TimeLabel::whereId($id)->first();
            $times_label->times()->delete();
           $times_label
            ->setTranslation('label', 'en',  $request->label_en)
            ->setTranslation('label', 'ar',   $request->label_ar);
            $times_label->save();
            foreach (explode(',',$request->types_of_vendor) as $type_of_vendor) {
                $type = new \Modules\Vendors\Entities\TimeTypeOfVendor();
                $type->time_id = $times_label->id;
                $type->type_of_vendor = trim($type_of_vendor);
                $type->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function show($id){
        return $this->model::with(['times.type_of_vendor','type_of_vendors'])->whereId($id)->first();
    }
    public function create(){
        return [
            "title" => "اضافة نوع توقيت جديد",
            "inputs" => [
                ['title' => 'الاسم بالعربية', 'input' => 'input', 'name' => 'label_ar', 'required' => true,'operations' => ['show' => ['text' => "label.ar",'update' => ['text' => "label.ar"]]]],
                ['title' => 'الاسم بالانجليزية', 'input' => 'input', 'name' => 'label_en', 'required' => true,'operations' => ['show' => ['text' => "label.en",'update' => ['text' => "label.en"]]]],
                [
                    'title' => 'انواع الموردين',
                    'input' => 'select',
                    'name' => 'types_of_vendor',
                    'classes' => ['select2'],
                    'required' => true,
                    'multiple' => true,
                    'data' => [
                        'options_source' => 'type_of_vendors'
                    ],
                    'operations' => [
                        'show' => ['text' => 'type_of_vendors.id', 'id' => 'type_of_vendors.id']
                    ]
                ],
            ]
        ];
    }
}
