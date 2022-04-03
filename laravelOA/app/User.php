<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    protected $appends = ['status_str','sex_str'];

    protected $sexDict = [
        0=>'女',
        1=>'男',
        2=>'未知'

    ];
    protected $statusDict = [
        0=>'禁用',
        1=>'正常',

    ];

    public function getSexStrAttribute(){
        return $this->sexDict[$this->sex];
    }
    public function getStatusStrAttribute(){
        return $this->statusDict[$this->status];
    }

    public function departStr()
    {
        return $this->belongsTo('App\Models\Depart','depart');
    }
}
