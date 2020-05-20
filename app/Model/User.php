<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'email', 
        'password',
        'serialNo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'detail', 
        'userRole.role',
        'reports',
        'quarantine',
        'positive',
    ];

    public function detail()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(UserReport::class, 'user_id');
    }

    public function quarantine()
    {
        return $this->hasOne(UserQuarantine::class, 'user_id')
            ->whereNull('deleted_at');
    }

    public function positive()
    {
        return $this->hasOne(UserPositive::class, 'user_id')
            ->whereNull('deleted_at');
    }

    public function userRole()
    {
        return $this->hasOne(UserRole::class, 'user_id');
    }

    public static function show($id)
    {
        return User::where('id', $id)->first();
    }

    public static function getPassword($id){
        $user = User::where('id',$id)->first();

        return $user->setVisible(['password'])->password;
    }

}
