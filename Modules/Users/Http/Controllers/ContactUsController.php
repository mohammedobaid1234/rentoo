<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContactUsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " إدارة  الاستفسارات ";
    private $model = \Modules\Users\Entities\ContactUs::class;
    
    public function manage(){
        $this->can('users_module_contact_us_manage', 'view');
        $data['activePage'] = ['users' => 'contact_us'];
        $data['breadcrumb'] = [
            ['title' => "الاستفسارات"],
            ['title' => $this->title]
        ];

        return view("users::contact_us", $data);
    }

    public function datatable(Request $request){
        $this->can('users_module_contact_us_manage');

        $eloquent = $this->model::with(['user']);
        $filters = [];
        $columns = [
            ['title' => 'اسم المرسل', 'column' => 'user.full_name'  ],
            ['title' => 'الرسالة ', 'column' => 'message'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
