<?php

namespace Modules\Vendors\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class VendorsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "الموردين";
    private $model = \Modules\Vendors\Entities\Vendor::class;
  
    public function manage(){
        $this->can('vendors_module_vendors_manage', 'view');
        $data['activePage'] = ['vendors' => 'vendors'];
        $data['breadcrumb'] = [
            ['title' => " انواع الموردين"],
            ['title' => $this->title]
        ];

        return view("vendors::vendors", $data);
    }

    public function datatable(Request $request){
        $this->can('vendors_module_vendors_manage');

        $eloquent = $this->model::with(['user', 'type']);
        $filters = [];
        $columns = [
            ['title' => 'اسم الشاليه ', 'column' => 'company_name' ,],
            ['title' => 'النوع', 'column' => 'type.name.ar' ,],
            ['title' => 'اسم المسؤول عن الشاليه ', 'column' => 'user.full_name' ],
            ['title' => 'رقم هاتف المسؤول', 'column' => 'user.mobile_no' ],
            ['title' => 'العنوان', 'column' => 'location', 'formatter' => 'locationAddress' ],
            ['title' => 'الاوقات ', 'column' => 'time', 'formatter' => 'times' ],
            ['title' => 'الحالة ', 'column' => 'status', 'formatter' => 'status' ],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']

        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function store(Request $request){
        \Auth::user()->authorize('vendors_module_vendors_store');

        $request->validate([
            'company_name' => 'required',
            'starting_time' => 'required',
            'closing_time' => 'required',
            'type_id' => 'required',
            'national_id' => 'required',
            'address' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        \DB::beginTransaction();
        try {

            $user =new \Modules\Users\Entities\User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->national_id = $request->national_id;
            $user->address = $request->address;
            $user->mobile_no = $request->mobile_no;
            $user->password = Hash::make('12345678');
            $user->save();

            $vendor = new \Modules\Vendors\Entities\Vendor;
            $vendor->company_name = $request->company_name;
            $vendor->starting_time = $request->starting_time;
            $vendor->closing_time = $request->closing_time;
            $vendor->user_id = $user->id;
            $vendor->type_id = $request->type_id;
            $vendor->location = $request->location;
            $vendor->created_by = \Auth::user()->id;
            $vendor->save();

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
            'company_name' => 'required',
            'starting_time' => 'required',
            'closing_time' => 'required',
            'type_id' => 'required',
            'national_id' => 'required',
            'address' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        \DB::beginTransaction();
        try {
            $vendor =  \Modules\Vendors\Entities\Vendor::whereId($id)->first();
            $vendor->company_name = $request->company_name;
            $vendor->starting_time = $request->starting_time;
            $vendor->closing_time = $request->closing_time;
            $vendor->location = $request->location;
            $vendor->type_id = $request->type_id;
            $vendor->created_by = \Auth::user()->id;
            $vendor->save();
            $user = \Modules\Users\Entities\User::where('id', $vendor->user_id)->first();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->national_id = $request->national_id;
            $user->address = $request->address;
            $user->mobile_no = $request->mobile_no;
            $user->password = Hash::make('12345678');
            $user->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);    }
    public function show($id){
        return $this->model::with(['user', 'type'])->whereId($id)->first();
    }

    public function create(){
        return [
            "title" =>"إضافة مورد جديد",
            "inputs" => [
                ['title' => 'رقم الهوية' , 'input' => 'input', 'name' => 'national_id', 'required' => true, 'operations' => ['show' => ['text' => 'user.national_id']]], 
                [
                    ['title' => 'الاسم الاول' , 'input' => 'input', 'name' => 'first_name', 'required' => true, 'operations' => ['show' => ['text' => 'user.first_name']]], 
                    ['title' => 'اسم العائلة ' , 'input' => 'input', 'name' => 'last_name', 'required' => true, 'operations' => ['show' => ['text' => 'user.last_name']]], 
                ],
                ['title' => 'رقم الجوال' , 'input' => 'input', 'name' => 'mobile_no', 'required' => true, 'operations' => ['show' => ['text' => 'user.mobile_no']]], 
                ['title' => 'النوع ', 'input' => 'select', 'name' => 'type_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'type_of_vendors', 'placeholder' => 'النوع...'],'operations' => ['show' => ['text' => 'type.id', 'id' => 'type_id']]],
                ['title' => 'اسم المكان' , 'input' => 'input', 'name' => 'company_name', 'required' => true, 'operations' => ['show' => ['text' => 'company_name']]], 
                ['title' => ' العنوان' , 'input' => 'input', 'name' => 'address', 'required' => true, 'operations' => ['show' => ['text' => 'user.address']]], 
                [
                    ['title' => 'يفتح  ', 'input' => 'input', 'name' => 'starting_time', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'starting_time']]],
                    ['title' => 'يغلق  ', 'input' => 'input', 'name' => 'closing_time', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'closing_time']]],
                ],
            ]
        ];
    }

    public function map(){
        $vendors = \Modules\Vendors\Entities\Vendor::get();
        return view('vendors::vendors_locations', [
            'vendors' => $vendors,
            'vendorLocations' => \Modules\Vendors\Entities\Vendor::pluck('location'),
        ]);
    }

    public function storeLocation(Request $request){
        $vendor = \Modules\Vendors\Entities\Vendor::whereId($request->vendor_id)->first();
        $vendor->location = $request->location;
    }
    public function getVendorLocation($id){
        $vendor = \Modules\Vendors\Entities\Vendor::whereId($id)->first();
        return $vendor; 
        return $vendor->location;
    }
}
