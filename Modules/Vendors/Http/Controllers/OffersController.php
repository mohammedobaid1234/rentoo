<?php

namespace Modules\Vendors\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OffersController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;
    private $title = "العروض";
    private $model = \Modules\Vendors\Entities\Offer::class;

    public function manage(){
        $this->can('vendors_module_vendor_offer_manage', 'view');
        $data['activePage'] = ['vendors' => 'vendor_offer'];
        $data['breadcrumb'] = [
            ['title' => " العروض"],
            ['title' => $this->title]
        ];

        return view("vendors::offers", $data);
    }

    public function datatable(Request $request){
        $this->can('vendors_module_vendor_offer_manage');

        $eloquent = $this->model::with(['vendor']);
        $filters = [];
        $columns = [
            ['title' => 'اسم الشاليه ', 'column' => 'vendor.company_name' ,],
            ['title' => 'اسم العرض', 'column' => 'name.ar' ,],
            ['title' => 'تاريخ الاتاحة ', 'column' => 'time', 'formatter' => 'times' ],
            ['title' => 'القيمة ', 'column' => 'value'],
            ['title' => 'الحالة ', 'column' => 'status', 'formatter' => 'status' ],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']

        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('vendors_module_vendors_store');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'starting_data' => 'required',
            'value' => 'required',
            'starting_data' => 'required',
            'ended_data' => 'required',
            'vendor_id' => 'required',
        ]);
        \DB::beginTransaction();
        try {
            $offer = new \Modules\Vendors\Entities\Offer();
            $offer
             ->setTranslation('name', 'en',  $request->name_en)
             ->setTranslation('name', 'ar',   $request->name_ar);
            $offer
             ->setTranslation('description', 'en',  $request->description_en)
             ->setTranslation('description', 'ar',   $request->description_ar);
             $offer->value = $request->value;
             $offer->starting_data = $request->starting_data;
             $offer->ended_data = $request->ended_data;
             $offer->vendor_id = $request->vendor_id;
             $offer->status = $request->status;
             $offer->created_by = \Auth::user()->id;
             $offer->save();
             if ($request->hasFile('image') && $request->file('image')[0]->isValid()) {
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "offer-image";

                $offer->addMediaFromRequest('image[0]')
                    ->usingFileName($media_new_name)
                    ->usingName($request->file('image')[0]->getClientOriginalName())
                    ->toMediaCollection($collection);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('vendors_module_vendors_store');

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'starting_date' => 'required',
            'value' => 'required',
            'starting_data' => 'required',
            'ended_data' => 'required',
            'vendor_id' => 'required',
        ]);
        \DB::beginTransaction();
        try {
            $offer =  \Modules\Vendors\Entities\Offer::whereId($id)->first();
            $offer
             ->setTranslation('name', 'en',  $request->name_en)
             ->setTranslation('name', 'ar',   $request->name_ar);
             $offer->save();
            $offer
             ->setTranslation('description', 'en',  $request->description_en)
             ->setTranslation('description', 'ar',   $request->description_ar);
             $offer->value = $request->value;
             $offer->starting_data = $request->starting_data;
             $offer->ended_data = $request->ended_data;
             $offer->vendor_id = $request->vendor_id;
             $offer->status = $request->status;
             $offer->created_by = \Auth::user()->id;
             $offer->save();
             if ($request->hasFile('image') && $request->file('image')[0]->isValid()) {
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "offer-image";

                $offer->addMediaFromRequest('image[0]')
                    ->usingFileName($media_new_name)
                    ->usingName($request->file('image')[0]->getClientOriginalName())
                    ->toMediaCollection($collection);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

    public function show($id){
        return $this->model::with(['vendor'])->whereId($id)->first();
    }

    public function create(){
        return [
            "inputs" => [
                ['title' => 'الاسم بالعربية', 'input' => 'input', 'name' => 'name_ar', 'required' => true,'operations' => ['show' => ['text' => "name.ar",'update' => ['text' => "name.ar"]]]],
                ['title' => 'الاسم بالانجليزية', 'input' => 'input', 'name' => 'name_en', 'required' => true,'operations' => ['show' => ['text' => "name.en",'update' => ['text' => "name.en"]]]],
                ['title' => 'الوصف بالعربية', 'input' => 'textarea', 'name' => 'description_ar', 'required' => true,'operations' => ['show' => ['text' => "description.ar",'update' => ['text' => "description.ar"]]]],
                ['title' => 'الوصف بالانجليزية', 'input' => 'textarea', 'name' => 'description_en', 'required' => true,'operations' => ['show' => ['text' => "description.en",'update' => ['text' => "description.en"]]]],
                ['title' => 'الحالة', 'classes' => ['select2'], 'input' => 'select','data' => ['options_source' => 'active'], 'name' => 'status', 'required' => true,'operations' => ['show' => ['text' => "active",'update' => ['text' => "active"]]]],
                ['title' => 'القيمة', 'input' => 'input', 'name' => 'value', 'required' => true,'operations' => ['show' => ['text' => "value",'update' => ['text' => "value"]]]],
                [
                    'title' => 'انواع الموردين',
                    'input' => 'select',
                    'name' => 'vendor_id',
                    'classes' => ['select2'],
                    'required' => true,
                    'multiple' => false,
                    'data' => [
                        'options_source' => 'vendors'
                    ],
                    'operations' => [
                        'show' => ['text' => 'vendors.id', 'id' => 'vendors.id']
                    ]
                ],
               
                [
                    ['title' => 'يفتح  ', 'input' => 'input', 'name' => 'starting_data', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'starting_data']]],
                    ['title' => 'يغلق  ', 'input' => 'input', 'name' => 'ended_data', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'ended_data']]],
                ],
                [
                    'title' => 'صورة العرض',
                    'input' => 'input',
                    'type' => 'file',
                    'name' => 'image',
                    'operations' => [
                        'show' => ['text' => 'image'],
                        'update' => ['text' => 'image'],
                    ]
                ]
            ]
        ];
    }
}
