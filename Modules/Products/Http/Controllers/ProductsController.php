<?php

namespace Modules\Products\Http\Controllers;

use Facade\FlareClient\View;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Products\Entities\CategoryAttributeType;

class ProductsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " انواع المنتجات ";
    private $model = \Modules\Products\Entities\Product::class;
    
    public function manage(){
        $this->can('Products_module_Products_manage', 'view');
        $data['activePage'] = ['Products' => 'Products'];
        $data['breadcrumb'] = [
            ['title' => "الحجوزات"],
            ['title' => $this->title]
        ];

        return view("Products::Products", $data);
    }

    public function datatable(Request $request){
        $this->can('Products_module_categories_manage');

        $eloquent = $this->model::with(['category','vendor']);
        $filters = [];
        $columns = [
            ['title' => 'الاسم بالعربي', 'column' => 'name_ar' , 'formatter' => 'name_ar' ],
            ['title' => 'الاسم بالانجليزي', 'column' => 'name_en', 'formatter' => 'name_en' ],
            ['title' => 'المورد', 'column' => 'vendor.company_name'],
            ['title' => 'السعر', 'column' => 'price'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function create(){
        return View('Products::create-product', ['vendors' => \Modules\Vendors\Entities\Vendor::get(), 'categories' => \Modules\Products\Entities\Category::get() ]);
     
    }
    public function edit($id){
        $item = \Modules\Products\Entities\Product::with('attributes')->whereId($id)->first();
        return View('Products::edit-product', ['item' => $item,'vendors' => \Modules\Vendors\Entities\Vendor::get(), 'categories' => \Modules\Products\Entities\Category::get() ]);
     
    }
    public function showProduct($id){
        $item = \Modules\Products\Entities\Product::with(['attributes', 'tags'])->whereId($id)->first();
        return response()->json($item);
    }
    public function getAttribute($id){
        $vendor = \Modules\Vendors\Entities\Vendor::whereId($id)->first();
        return response()->json(CategoryAttributeType::with(['type', 'type.list'])->where('category_id', $vendor->type_id)->get());

    }
    public function getTags($id){
        $vendor = \Modules\Vendors\Entities\Vendor::whereId($id)->first();
        return response()->json(\Modules\Products\Entities\Tag::with(['vendor_type'])->where('vendor_type_id', $vendor->type_id)->get());

    }

    public function store(Request $request){
        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'vendor_id' => 'required',
            'price' => 'required',
           
        ]);
        \DB::beginTransaction();
        try {
            $product = new \Modules\Products\Entities\Product;
            $product
            ->setTranslation('name', 'en', $request->name_en)
            ->setTranslation('name', 'ar',  $request->name_ar);
            $product
            ->setTranslation('description', 'en', $request->description_en)
            ->setTranslation('description', 'ar',  $request->description_ar);
            $product->vendor_id = $request->vendor_id;
            $product->price = $request->price;
            $product->save();
            self::syncProductAttributes($product, $request->get('attributes'));
            self::syncProductTags($product, explode(',',$request->tags));

            \DB::commit();
        } catch (\Exception $e) {

            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }
        return response()->json(['message' => 'ok', 'data' => $product]);
       
    }
    public function update(Request $request, $id){
        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'vendor_id' => 'required',
            'price' => 'required',
           
        ]);
        \DB::beginTransaction();
        try {
            $product =  \Modules\Products\Entities\Product::whereId($id)->first();
            $product
            ->setTranslation('name', 'en', $request->name_en)
            ->setTranslation('name', 'ar',  $request->name_ar);
            $product
            ->setTranslation('description', 'en', $request->description_en)
            ->setTranslation('description', 'ar',  $request->description_ar);
            $product->vendor_id = $request->vendor_id;
            $product->price = $request->price;
            $product->save();
            self::syncProductAttributes($product, $request->get('attributes'));
            self::syncProductTags($product, explode(',',$request->tags));

            \DB::commit();
        } catch (\Exception $e) {

            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }
        return response()->json(['message' => 'ok', 'data' => $product]);
       
    }
    public function addImage(Request $request){
        $id = $request->userId;
        $product = \Modules\Products\Entities\Product::whereId($id)->first();
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $extension = strtolower($request->file('file')->extension());
            $media_new_name = strtolower(md5(time())) . "." . $extension;
            $collection = "product-image";
            
            $product->addMediaFromRequest('file')
                ->usingFileName($media_new_name)
                ->usingName($request->file('file')->getClientOriginalName())
                ->toMediaCollection($collection);
            return response()->json(['message' => 'ok']);
        }
    }
    private static function syncProductAttributes($product, $attributes){
        $productAttributes = \Modules\Products\Entities\ProductAttribute::where('product_id', $product->id)->get();
        $productAttributesRemained = [];
        if(count($productAttributes) !== 0){
            foreach($productAttributes as $productAttribute){
                $productAttributesRemained[$productAttribute->id] = false;
            }
        }
        
        foreach($attributes as $key => $attribute){
            $productAttribute = \Modules\Products\Entities\ProductAttribute::where('product_id', $product->id)->where('type_id', $attribute['id'])->first();

            if(!$productAttribute){
                $productAttribute = new \Modules\Products\Entities\ProductAttribute;
                $productAttribute->product_id = $product->id;
                $productAttribute->type_id = $attribute['id'];
                $productAttribute->created_by = \Auth::user()->id;
            }

            $productAttribute->value = trim($attribute['value']);
            $productAttribute->save();

            count($productAttributesRemained) !== 0 ? $productAttributesRemained[$productAttribute->id] = true : '';
        }
        if(count($productAttributesRemained) !== 0 ){
            foreach($productAttributesRemained as $productAttributeId => $remained){
                if(!$remained){
                    \Modules\Products\Entities\ProductAttribute::whereId($productAttributeId)->delete();
                }
            }
        }
    }
    private static function syncProductTags($product, $tags){
        $ProductTag = \Modules\Products\Entities\ProductTag::where('product_id', $product->id)->get();
        $ProductTagRemained = [];
        if(count($ProductTag) !== 0){
            foreach($ProductTag as $ProductTag){
                $ProductTagRemained[$ProductTag->id] = false;
            }
        }
        
        foreach($tags as $tag){
            $ProductTag = \Modules\Products\Entities\ProductTag::where('product_id', $product->id)->where('tag_id', $tag)->first();

            if(!$ProductTag){
                $ProductTag = new \Modules\Products\Entities\ProductTag;
                $ProductTag->product_id = $product->id;
                $ProductTag->tag_id = $tag;
            }

            $ProductTag->save();

            count($ProductTagRemained) !== 0 ? $ProductTagRemained[$ProductTag->id] = true : '';
        }
        if(count($ProductTagRemained) !== 0 ){
            foreach($ProductTagRemained as $ProductTagId => $remained){
                if(!$remained){
                    \Modules\Products\Entities\ProductTag::whereId($ProductTagId)->delete();
                }
            }
        }
    }

    public function show($id){
        $product = \Modules\Products\Entities\Product::whereId($id)->first();
        $images = $product->getMedia('product-image');
        $images_new  = collect([]);
        foreach($images as $image){
            $new['url'] =  url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
            $new['name'] = $image->file_name;
            $images_new->push($new);
        }
        return response()->json($images_new,200);
    }
}
