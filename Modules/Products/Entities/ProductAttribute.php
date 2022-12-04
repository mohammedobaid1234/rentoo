<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model{
    use SoftDeletes;

    protected $table = 'pm_product_attributes';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    
   
    public function product(){
        return $this->belongsTo(\Modules\Products\Entities\PRoduct::class,'product_id');
    }
    public function attribute(){
        return $this->belongsTo(\Modules\Products\Entities\AttributeType::class,'type_id');
    }

}
