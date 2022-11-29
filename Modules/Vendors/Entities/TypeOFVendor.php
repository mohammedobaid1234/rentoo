<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TypeOFVendor extends Model implements HasMedia{
    use \Modules\BriskCore\Traits\ModelTrait;
    use HasTranslations;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'vn_types_of_vendors';
    protected $appends = ['vendor_type_image_url'];
    public $translatable = ['name'];
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getVendorTypeImageUrlAttribute(){
        $image = $this->getMedia('vendor-type-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/assets/images/avatars/avatar6.png');

    }
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }

}
