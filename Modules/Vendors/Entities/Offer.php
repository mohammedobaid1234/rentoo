<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Offer extends Model  implements HasMedia{
    use SoftDeletes;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'vn_offers';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    public $translatable = ['name', 'description'];
    protected $appends = ['image_url'];


    public function getImageUrlAttribute(){
        $image = $this->getMedia('attribute-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/assets/images/avatars/avatar6.png');

    }

    public function vendor(){
        return $this->belongsTo(\Modules\Vendors\Entities\Vendor::class, 'vendor_id');
    }
}
