<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandProduct extends Model{
    use SoftDeletes;
    protected $table = 'vn_brand_product';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

}
