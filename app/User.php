<?php

namespace App;

/*------------------------------------------
            Permission Manager
------------------------------------------*/
use Backpack\CRUD\CrudTrait; 
use Spatie\Permission\Traits\HasRoles;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;

class User extends Authenticatable
{
    use CrudTrait;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id', 'notes'
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
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

     public function company()
    {
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
