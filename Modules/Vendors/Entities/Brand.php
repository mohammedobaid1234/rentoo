<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model{
    use SoftDeletes;
    protected $table = 'vn_brands';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    public function getImageUrlAttribute(){
        $image = $this->getMedia('brand-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/assets/images/avatars/avatar6.png');

    }
}
