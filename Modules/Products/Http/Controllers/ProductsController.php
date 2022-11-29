<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " انواع التوقيت ";
    private $model = \Modules\Products\Entities\Product::class;
    
    public function manage(){
        $this->can('products_module_products_manage', 'view');
        $data['activePage'] = ['products' => 'products'];
        $data['breadcrumb'] = [
            ['title' => "المنتجات"],
            ['title' => $this->title]
        ];

        return view("products::products", $data);
    }

    public function datatable(Request $request){
        $this->can('products_module_categories_manage');

        $eloquent = $this->model::with(['category','vendor']);
        $filters = [];
        $columns = [
            ['title' => 'الاسم بالعربي', 'column' => 'name_ar' , 'formatter' => 'name_ar' ],
            ['title' => 'الاسم بالانجليزي', 'column' => 'name_en', 'formatter' => 'name_en' ],
            ['title' => 'التصنيف', 'column' => 'category.name.ar'],
            ['title' => 'المورد', 'column' => 'category.name.ar'],
            ['title' => 'الخصائص', 'column' => 'attributes', 'formatter' => 'attributes'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
