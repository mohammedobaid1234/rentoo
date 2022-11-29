<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeTypeOfVendor extends Model{
    use SoftDeletes;
    protected $table = 'vn_time_type_of_vendor';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    
    public function type_of_vendor(){
        return $this->belongsTo(\Modules\Vendors\Entities\TypeOFVendor::class, 'type_of_vendor');
    }
    public function time_label(){
        return $this->belongsTo(\Modules\Vendors\Entities\TimeLabel::class, 'time_id');
    }
}
