<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $fillable = [
        'hire_date', 'id', 'working_hours'
    ];

    public function user(){
        return $this->morphMany('App\User','userable');
    }
    public function semesters(){
        return $this->hasMany('App\Semester','admin_id');
    }
    public function departments(){
        return $this->hasMany('App\Department','admin_id');
    }
    public function places(){
        return $this->hasMany('App\Place','admin_id');
    }
    public function subjects(){
        return $this->hasMany('App\Subject','admin_id');
    }
    public function timetables(){
        return $this->hasMany('App\Timetable','admin_id');
    }
    public function open_courses(){
        return $this->hasMany('App\Open_course','admin_id');
    }
}
