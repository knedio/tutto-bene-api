<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    protected $fillable = [
        'user_id',
        'created_by',
        'findings',
        'isWell',
    ];

    protected $with = [
        'creator.detail', 
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model->findings = json_encode($model->findings);
        });
        self::updating(function ($model){
            $model->findings = json_encode($model->findings);
        });
    }
    
	public function getFindingsAttribute($findings)
	{
		return is_array($findings) ? $findings : json_decode($findings);
    }

	public function user()
	{
		return $this->belongsTo('App\Model\User', 'user_id');
	}

	public function creator()
	{
		return $this->belongsTo('App\Model\User', 'created_by');
	}

}
