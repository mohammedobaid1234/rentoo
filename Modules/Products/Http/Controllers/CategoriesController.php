<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoriesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "التصنيفات";
    private $model = \Modules\Products\Entities\Category::class;
    
    public function manage(){
        $this->can('products_module_categories_manage', 'view');
        $data['activePage'] = ['categories' => 'categories'];
        $data['breadcrumb'] = [
            ['title' => "التصنيفات"],
            ['title' => $this->title]
        ];

        return view("products::categories", $data);
    }

    public function datatable(Request $request){
        $this->can('products_module_categories_manage');

        $eloquent = $this->model::with(['parent','type_of_vendor']);
        $filters = [];
        $columns = [
            ['title' => 'الاسم بالعربي', 'column' => 'name_ar' , 'formatter' => 'name_ar' ],
            ['title' => 'الاسم بالانجليزي', 'column' => 'name_en', 'formatter' => 'name_en' ],
            ['title' => 'التصنيف الاب', 'column' => 'parent.name.ar'],
            ['title' => 'نوع المورد', 'column' => 'type_of_vendor.name.ar'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']

        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('vendors_module_times_name_store');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'types_of_vendor' => 'required',
        ]);
        \DB::beginTransaction();
        try {
           $category = new \Modules\Products\Entities\Category();
           $category
            ->setTranslation('name', 'en',  $request->name_en)
            ->setTranslation('name', 'ar',   $request->name_ar);
            $category->parent_id = $request->parent_id;
            $category->vendor_type =$request->type_of_vendor;
            $category->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('vendors_module_times_name_update');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'types_of_vendor' => 'required',
        ]);
        \DB::beginTransaction();
        try {
           $category =  \Modules\Products\Entities\Category::whereId($id)->first();
           $category
            ->setTranslation('name', 'en',  $request->name_en)
            ->setTranslation('name', 'ar',   $request->name_ar);
            $category->parent_id = $request->parent_id;
            $category->vendor_type =$request->types_of_vendor;
            $category->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function show($id){
        return $this->model::with(['parent','type_of_vendor'])->whereId($id)->first();
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
                    'name' => 'types_of_vendor',
                    'classes' => ['select2'],
                    'required' => true,
                    'multiple' => true,
                    'data' => [
                        'options_source' => 'type_of_vendors'
                    ],
                    'operations' => [
                        'show' => ['text' => 'type_of_vendor.id', 'id' => 'type_of_vendor.id']
                    ]
                ],
                // [
                //     'title' => 'التصنيف الاب',
                //     'input' => 'select',
                //     'name' => 'category_id',
                //     'classes' => ['select2'],
                //     'required' => false,
                //     'multiple' => true,
                //     'data' => [
                //         'options_source' => 'categories'
                //     ],
                //     'operations' => [
                //         'show' => ['text' => 'categories.id', 'id' => 'categories.id']
                //     ]
                // ],
            ]
        ];
    }
}
