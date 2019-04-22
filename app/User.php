<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'Doc'=>'App\Doctor',
    'Adm'=>'App\Admin',
    'Stu'=>'App\Student',
    'T_A'=>'App\Teacher_assistant',
    'S_A'=>'App\Student_affair',
]);

class User extends Authenticatable
{
    use Notifiable;
    protected  $primaryKey = 'userable_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name_en', 'name_ar', 'email', 'number', 'national_id', 'address', 'phone', 'DOB', 'gender' , 'userable_id' , 'userable_type' , 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function userable()
    {
        return $this->morphTo();
    }
    public function sending_mails(){
        return $this->hasMany('App\Mail','sender_id');
    }
    public function receiving_mails(){
        return $this->hasMany('App\Mail','receiver_id');
    }
}
