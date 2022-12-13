<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Tag extends Model{
    use SoftDeletes;
    use HasTranslations;
    protected $table = 'pm_tags';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    public $translatable = ['name'];

    public function vendor_type(){
        return $this->belongsTo(\Modules\Vendors\Entities\TypeOFVendor::class, 'vendor_type_id');
    }
}
