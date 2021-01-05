<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, LogsActivity, HasRoles; 
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = ['password', 'updated_at'];

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'birthdate', 'user_contact', 'status', 'email', 'password',
    ];

    protected static $logAttributes = 
    [
        'first_name', 'last_name', 'gender', 'birthdate', 'user_contact', 'status', 'email', 'password',
    ];

    protected static $logOnlyDirty = true;
    protected static $logName = 'User';
    

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

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A user has been {$eventName}";
    }
}
