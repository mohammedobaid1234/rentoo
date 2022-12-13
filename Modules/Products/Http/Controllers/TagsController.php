<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TagsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "التاجات";
    private $model = \Modules\Products\Entities\Tag::class;
    
    public function manage(){
        $this->can('Products_module_tags_manage', 'view');
        $data['activePage'] = ['tags' => 'tags'];
        $data['breadcrumb'] = [
            ['title' => "التاجات"],
            ['title' => $this->title]
        ];

        return view("Products::tags", $data);
    }

    public function datatable(Request $request){
        $this->can('Products_module_tags_manage');

        $eloquent = $this->model::with(['vendor_type']);
        $filters = [];
        $columns = [
            ['title' => 'الاسم بالعربي', 'column' => 'name_ar' , 'formatter' => 'name_ar' ],
            ['title' => 'الاسم بالانجليزي', 'column' => 'name_en', 'formatter' => 'name_en' ],
            ['title' => 'نوع التصنيف', 'column' => 'vendor_type.name.ar'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']

        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('Products_module_tags_store');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'vendor_type_id' => 'required',
        ]);
        \DB::beginTransaction();
        try {
           $tag = new \Modules\Products\Entities\Tag();
           $tag
            ->setTranslation('name', 'en',  $request->name_en)
            ->setTranslation('name', 'ar',   $request->name_ar);
            $tag->vendor_type_id =$request->vendor_type_id;
            $tag->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('Products_module_tags_update');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'vendor_type_id' => 'required',
        ]);
        \DB::beginTransaction();
        try {
           $tag =  \Modules\Products\Entities\Tag::whereId($id)->first();
           $tag
            ->setTranslation('name', 'en',  $request->name_en)
            ->setTranslation('name', 'ar',   $request->name_ar);
            $tag->vendor_type_id =$request->vendor_type_id;
            $tag->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function show($id){
        return $this->model::with(['vendor_type'])->whereId($id)->first();
    }

    public function create(){
        return [
            "title" => "اضافة نوع توقيت جديد",
            "inputs" => [
                ['title' => 'الاسم بالعربية', 'input' => 'input', 'name' => 'name_ar', 'required' => true,'operations' => ['show' => ['text' => "name.ar",'update' => ['text' => "name.ar"]]]],
                ['title' => 'الاسم بالانجليزية', 'input' => 'input', 'name' => 'name_en', 'required' => true,'operations' => ['show' => ['text' => "name.en",'update' => ['text' => "name.en"]]]],
                [
                    'title' => 'انواع الموردين',
                    'input' => 'select',
                    'name' => 'vendor_type_id',
                    'classes' => ['select2'],
                    'required' => true,
                    'data' => [
                        'options_source' => 'type_of_vendors'
                    ],
                    'operations' => [
                        'show' => ['text' => 'vendor_type_id', 'id' => 'vendor_type_id']
                    ]
                ],
                // [
                //     'title' => 'التصنيف الاب',
                //     'input' => 'select',
                //     'name' => 'vendor_type_id',
                //     'classes' => ['select2'],
                //     'required' => false,
                //     'multiple' => true,
                //     'data' => [
                //         'options_source' => 'tags'
                //     ],
                //     'operations' => [
                //         'show' => ['text' => 'tags.id', 'id' => 'tags.id']
                //     ]
                // ],
            ]
        ];
    }
}
