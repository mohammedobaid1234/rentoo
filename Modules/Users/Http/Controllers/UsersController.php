<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UsersController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "مستخدمين النظام";
    private $model = \Modules\Users\Entities\User::class;

    public function manage(){
        $this->can('users_module_users_manage', 'view');
        $data['activePage'] = ['users' => 'users'];
        $data['breadcrumb'] = [
            ['title' => "إدارة النظام"],
            ['title' => $this->title]
        ];

        return view("users::users", $data);
    }

    public function datatable(Request $request){
        $this->can('users_module_users_manage');

        $eloquent = $this->model::with([]);
        if((int) $request->filters_status){
            if(trim($request->name) !== ""){
               $eloquent->where('name', "Like", "%".$request->name . "%");
            }
            if(trim($request->mobile_no) !== ""){
               $eloquent->where('mobile_no', "Like", "%".$request->mobile_no . "%");
            }
            if(trim($request->email) !== ""){
               $eloquent->where('email', "Like", "%".$request->email . "%");
            }
            if(trim($request->created_at) !== ""){
                $eloquent->whereCreatedAt($request->created_at);
            }
           
        }

        $filters = [
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'name'],
            ['title' => 'رقم الجوال', 'type' => 'input', 'name' => 'mobile_no'],
            ['title' => 'البريد الإلكتروني'  , 'type' => 'input', 'name' => 'email'],
            ['title' => 'تاريخ التسجيل', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'الرقم الوطني', 'column' => 'national_id'],
            ['title' => 'الاسم', 'column' => 'full_name'],
            ['title' => 'رقم الجوال', 'column' => 'mobile_no'],
            ['title' => 'البريد الإلكتروني', 'column' => 'email'],
            ['title' => 'العنوان', 'column' => 'address'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
