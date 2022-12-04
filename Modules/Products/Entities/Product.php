<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia{
    use SoftDeletes;
    use HasTranslations;
    use InteractsWithMedia;
    use \Modules\BriskCore\Traits\ModelTrait;

    
    protected $fillable = [
        'product_code','name', 'description','category_id', 'vendor_id', 'currency_id',
        'price','quantity', 'status_id', 'created_by', 'product_code'
    ];
    protected $table = 'pm_products';
    public $translatable = ['name', 'description'];
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    protected $appends = ['image_url'];
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getImageUrlAttribute(){
        $image = $this->getMedia('product-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/assets/images/avatars/avatar6.png');

    }
    public function getImagesAttribute(){
        return  $this->getMedia('product-image');
    }
    public function category(){
        return $this->belongsTo(\Modules\Products\Entities\Category::class,'category_id');
    }
    public function vendor(){
        return $this->belongsTo(\Modules\Vendors\Entities\Vendor::class,'vendor_id');
    }
    
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }

    public function ratings(){
        return $this->morphMany(\Modules\Users\Entities\Rate::class, 'rateable', 'rateable_type', 'rateable_id', 'id');
    }
    public function type(){
        return $this->morphOne(\Modules\Core\Entities\Feature::class, 'typeable');
    }
    public function favorite(){
        return $this->hasMany(\Modules\Users\Entities\Favorite::class);
    }
    public function offer(){
        return $this->belongsToMany(\Modules\Vendors\Entities\Offer::class, 'vn_offers_products');
    }
    public function attributes(){
        return $this->hasMany(\Modules\Products\Entities\ProductAttribute::class, 'product_id');
    }

}
