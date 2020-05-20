<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'firstName', 
        'middleName', 
        'lastName', 
        'address', 
        'gender',
        'qrCode',
    ];
}
