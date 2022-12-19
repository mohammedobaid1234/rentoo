<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia{
    use SoftDeletes;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'pm_categories';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    public $translatable = ['name', 'description'];
    protected $appends = ['image_url', 'name_ar'];
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getImageUrlAttribute(){
        $image = $this->getMedia('category-image')->first();
        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/assets/images/avatars/avatar6.png');

    }
    public function image(){
        return $this->morphOne(Media::class,'model');
    }
    
    
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function type_of_vendor(){
        return $this->belongsTo(\Modules\Vendors\Entities\TypeOFVendor::class, 'vendor_type');
    }
    public function parent(){
        return $this->belongsTo(\Modules\Products\Entities\Category::class, 'parent_id');
    }
    public function children(){
        return $this->hasMany(\Modules\Products\Entities\Category::class, 'parent_id');
    }
    public function getNameArAttribute(){
        return $this->getTranslations('name')['ar'];
    }
}

