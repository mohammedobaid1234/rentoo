<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTag extends Model{
    use SoftDeletes;
    protected $table = 'pm_product_tag';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
}
