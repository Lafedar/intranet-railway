<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MyResetPassword;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'dni', 'password', 'remember_token', 'email_verified_at',
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
     * Get the password reset notification mail.
     *
     * @param  string  $token
     * @return \App\Notifications\MyResetPassword
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));  //aca se rompio y no enviaba mails
    }
}