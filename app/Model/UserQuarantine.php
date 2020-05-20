<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserQuarantine extends Model
{
    protected $fillable = [
        'user_id',
        'created_by',
        'days',
        'status',
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
