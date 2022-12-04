<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactUs extends Model{
    protected $table = 'um_contact_us';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    
    public function user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class);
    }
   
}
