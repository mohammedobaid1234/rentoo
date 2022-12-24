<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserRegistrationController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " إدارة  الحجوزات ";
    private $model = \Modules\Users\Entities\UserRegistration::class;
    
    public function manage(){
        $this->can('users_module_registrations_manage', 'view');
        $data['activePage'] = ['registrations' => 'registrations'];
        $data['breadcrumb'] = [
            ['title' => "الحجوزات"],
            ['title' => $this->title]
        ];

        return view("users::registrations", $data);
    }

    public function datatable(Request $request){
        $this->can('users_module_registrations_manage');

        $eloquent = $this->model::with(['user','product', 'time']);
        $filters = [];
        $columns = [
            ['title' => 'الاسم الحاجز', 'column' => 'user.full_name'  ],
            ['title' => 'الاسم المكان', 'column' => 'product.name.ar' ],
            ['title' => 'التوقيت', 'column' => 'time.label.ar'],
            ['title' => 'السعر', 'column' => 'price'],
            ['title' => 'الحالة', 'column' => 'status','formatter' => 'status'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']

        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function changeStatus(Request $request,$id){
        $item = \Modules\Users\Entities\UserRegistration::whereId($id)->first();
        $item->status = $request->status;
        $item->save();
        return response()->json(['message', 'ok']);
    }
}
