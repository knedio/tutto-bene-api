<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserPositive extends Model
{
    protected $fillable = [
        'user_id',
        'created_by',
        'isPositive',
    ];

    protected $with = [
        'creator.detail', 
    ];

	public function user()
	{
		return $this->belongsTo('App\Model\User', 'user_id');
	}

	public function creator()
	{
		return $this->belongsTo('App\Model\User', 'created_by');
	}
}
