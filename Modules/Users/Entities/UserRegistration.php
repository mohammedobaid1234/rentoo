<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRegistration extends Model{
    protected $table = 'um_registration';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    
    public function user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class);
    }
    public function product(){
        return $this->belongsTo(\Modules\PRoducts\Entities\Product::class);
    }
    public function time(){
        return $this->belongsTo(\Modules\Vendors\Entities\TimeLabel::class, 'time_id');
    }
}
