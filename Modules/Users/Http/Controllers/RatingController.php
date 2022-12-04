<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RatingController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " إدارة  التقييمات ";
    private $model = \Modules\Users\Entities\Rating::class;
    
    public function manage(){
        $this->can('users_module_rating_manage', 'view');
        $data['activePage'] = ['users' => 'rating'];
        $data['breadcrumb'] = [
            ['title' => "التقييمات"],
            ['title' => $this->title]
        ];

        return view("users::rating", $data);
    }

    public function datatable(Request $request){
        $this->can('users_module_rating_manage');

        $eloquent = $this->model::with(['user','product']);
        $filters = [];
        $columns = [
            ['title' => 'اسم المرسل', 'column' => 'user.full_name'  ],
            ['title' => 'اسم الخدمة', 'column' => 'product.name.ar'  ],
            ['title' => 'التقييم ', 'column' => 'rate'  ],
            ['title' => 'الرسالة ', 'column' => 'feedback'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at'],
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
