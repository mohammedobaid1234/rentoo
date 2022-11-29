<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TimeLabel extends Model{
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'vn_time_label';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    public $translatable = ['label'];
    
    public function times(){
        return $this->hasMany(\Modules\Vendors\Entities\TimeTypeOfVendor::class, 'time_id');
    }
    public function type_of_vendors(){
        return $this->belongsToMany(\Modules\Vendors\Entities\TypeOFVendor::class, 'vn_time_type_of_vendor','time_id','type_of_vendor')->wherePivot('deleted_at', null);
    }


    
}
