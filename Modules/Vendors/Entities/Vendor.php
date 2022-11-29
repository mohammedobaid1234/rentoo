<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model implements HasMedia{
    use SoftDeletes, Notifiable;
    use InteractsWithMedia;

    protected $table = 'vn_vendors';
    protected $appends = ['vendor_logo_url'];
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    public $translatable = ['name'];

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getVendorLogoUrlAttribute(){
        $image = $this->getMedia('vendor-logo-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/assets/images/avatars/avatar6.png');

    }
    public function getImagesAttribute(){
        return  $this->getMedia('vendor-logo-image');
    }
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class);
    }
    public function type(){
        return $this->belongsTo(\Modules\Vendors\Entities\TypeOFVendor::class, 'type_id');
    }
    public function times_label(){
        return $this->hasMany(\Modules\Vendors\Entities\TimeLabel::class, 'type_id');
    }
    
    public function products(){
        return $this->hasMany(\Modules\Products\Entities\Product::class,'vendor_id');
    }
    public function children(){
        return $this->hasMany(\Modules\Vendors\Entities\Vendors::class, 'parent_id');
    }
    public function parent(){
        return $this->belongsTo(\Modules\Vendors\Entities\Vendors::class, 'parent_id');
    }
    
}
