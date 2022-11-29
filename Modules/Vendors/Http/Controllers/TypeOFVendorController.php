<?php

namespace Modules\Vendors\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TypeOFVendorController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " انواع الموردين ";
    private $model = \Modules\Vendors\Entities\TypeOFVendor::class;

    public function index(){
        $types = $this->model::get();
        // return $types;
        $collection = collect([]);
        $data = [];
        foreach ($types as $type) {
            $data['id'] = $type->id;
            $data['name'] = $type->getTranslation('name', 'ar');
            $collection->push([
                'id' => $type->id,
                'name' => $type->getTranslation('name', 'ar')
            ]);
        }
        return $collection;
    }
    public function manage(){
        $this->can('vendors_module_type_of_vendors_manage', 'view');
        $data['activePage'] = ['vendors' => 'type_of_vendors'];
        $data['breadcrumb'] = [
            ['title' => "الموردين"],
            ['title' => $this->title]
        ];

        return view("vendors::type_of_vendors", $data);
    }

    public function datatable(Request $request){
        $this->can('vendors_module_type_of_vendors_manage');

        $eloquent = $this->model::with([]);
        $filters = [];
        $columns = [
            ['title' => 'الاسم بالعربي', 'column' => 'name_ar' , 'formatter' => 'name_ar' ],
            ['title' => 'الاسم بالانجليزي', 'column' => 'name_en', 'formatter' => 'name_en' ],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']

        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function store(Request $request){
        \Auth::user()->authorize('vendors_module_type_of_vendors_store');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);

        \DB::beginTransaction();
        try {
           $typeOfVendor = new \Modules\Vendors\Entities\TypeOFVendor;
           $typeOfVendor
            ->setTranslation('name', 'en',  $request->name_en)
            ->setTranslation('name', 'ar',   $request->name_ar);
            $typeOfVendor->created_by  = \Auth::user()->id;
            $typeOfVendor->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('vendors_module_type_of_vendors_update');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);

        \DB::beginTransaction();
        try {
           $typeOfVendor =  \Modules\Vendors\Entities\TypeOFVendor::whereId($id)->first();
           $typeOfVendor
            ->setTranslation('name', 'en',  $request->name_en)
            ->setTranslation('name', 'ar',   $request->name_ar);
            $typeOfVendor->created_by  = \Auth::user()->id;
            $typeOfVendor->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function show($id){
        return $this->model::whereId($id)->first();
    }
    public function create(){
        return [
            "title" => "اضافة نوع مورد جديد",
            "inputs" => [
                ['title' => 'الاسم بالعربية', 'input' => 'input', 'name' => 'name_ar', 'required' => true,'operations' => ['show' => ['text' => "name.ar",'update' => ['text' => "name.ar"]]]],
                ['title' => 'الاسم بالانجليزية', 'input' => 'input', 'name' => 'name_en', 'required' => true,'operations' => ['show' => ['text' => "name.en",'update' => ['text' => "name.en"]]]],
            ]
        ];
    }
}
