<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationCode extends Model{
    use SoftDeletes;
	public $table = 'um_verification_codes'; 
	protected $hidden = ['updated_at','deleted_at'];
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
}
