<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryAttributeTypesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " مواصفات انواع الموردين  ";
    private $model = \Modules\Products\Entities\CategoryAttributeType::class;
    
    public function manage(){
        $this->can('products_module_category_attribute_types_manage', 'view');
        $data['activePage'] = ['category_attribute_types' => 'category_attribute_types'];
        $data['breadcrumb'] = [
            ['title' => "مواصفات انواع الموردين "],
            ['title' => $this->title]
        ];

        return view("products::category_attribute_types", $data);
    }

    public function datatable(Request $request){
        $this->can('products_module_category_attribute_types_manage');

        $eloquent = $this->model::with(['category','attribute.list']);
        $filters = [];
        $columns = [
            ['title' => 'اسم التصنيف', 'column' => 'category_name' , 'formatter' => 'category_name' ],
            ['title' => 'اسم المواصفة', 'column' => 'attribute_name', 'formatter' => 'attribute_name' ],
            ['title' => 'الاختيارات', 'column' => 'attributes', 'formatter' => 'attributes'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function store(Request $request){
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'types_of_vendor' => 'required',
        ]);
        \DB::beginTransaction();
        try {
            $types_of_vendor = explode(',',$request->types_of_vendor ) ?? [];
            $request->list_name && count($request->list_name) != 0 ? $list_names = explode(',' ,$request->list_name[0] ) :$list_names =  [];
            $attribute =new \Modules\Products\Entities\AttributeType;
            $attribute
            ->setTranslation('name', 'en', $request->name_en)
            ->setTranslation('name', 'ar',  $request->name_ar);
            $attribute->save();
            if(count($types_of_vendor) == 0){
                return response()->json(['message' => 'رجاء اضافة التصنيف'], 403);
            }
            foreach ($types_of_vendor as $type) {
                $attribute_category = new \Modules\Products\Entities\CategoryAttributeType;
                $attribute_category->attribute_type_id = $attribute->id;
                $attribute_category->category_id = trim($type);
                $attribute_category->save();
            }
            if($list_names && count($list_names) != 0){
                foreach ($list_names as $list_name) {
                    $attribute_list =new \Modules\Products\Entities\AttributeTypeValue;
                    $attribute_list->attribute_type_id = $attribute->id;
                    $attribute_list->name = trim($list_name);
                    $attribute_list->save();
                }
            }

            \DB::commit();

        } catch (\Exception $e) {

            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }
        return response()->json(['message' => 'ok', 'data'=> $attribute]);
    }

    public function show($id){
        return $this->model::with(['category','attribute.list'])->whereId($id)->first();
    }

    public function create(){
        return [
            "title" => "اضافة نوع توقيت جديد",
            "inputs" => [
                ['title' => 'الاسم بالعربية', 'input' => 'input', 'name' => 'name_ar', 'required' => true,'operations' => ['show' => ['text' => "attribute.name.ar",'update' => ['text' => "attribute.name.ar"]]]],
                ['title' => 'الاسم بالانجليزية', 'input' => 'input', 'name' => 'name_en', 'required' => true,'operations' => ['show' => ['text' => "attribute.name.en",'update' => ['text' => "attribute.name.en"]]]],
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
                        'show' => ['text' => 'category.id', 'id' => 'category.id']
                    ]
                ],
                // ['title' => 'الاختيارات', 'input' => 'input', 'name' => 'list_name[]', 'required' => false ],

                
            ]
        ];
    }
    public function list($id){
        return $list = \Modules\Products\Entities\AttributeTypeValue::where('attribute_type_id', $id)->pluck('name');
    }
}
