<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CategoryAttributeType extends Model implements HasMedia{
    use SoftDeletes;
    use InteractsWithMedia;


    protected $table = 'pm_product_category_attribute_types';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(){
        $image = $this->getMedia('offer-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/assets/images/avatars/avatar6.png');

    }
    public function category(){
        return $this->belongsTo(\Modules\Vendors\Entities\TypeOFVendor::class,'category_id');
    }
    public function attribute(){
        return $this->belongsTo(\Modules\Products\Entities\AttributeType::class,'attribute_type_id');
    }
    public function type(){
        return $this->belongsTo(\Modules\Products\Entities\AttributeType::class, 'attribute_type_id');
    }
}
