<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $fillable = [
        'hire_date', 'id'
    ];

    public function user(){
        return $this->morphMany('App\User','userable');
    }
    public function experiences() {
        return $this->morphMany('App\Experience','experienceable');
    }
    public function grade()
    {
        return $this->morphOne('App\Grade', 'gradable');
    }
    public function timetables() {
        return $this->morphMany('App\Timetable','timetableable');
    }
    public function open_courses(){
        return $this->hasMany('App\Open_course','doctor_id');
    }
    public function materials(){
        return $this->hasMany('App\Material','doctor_id');
    }
    /*Leader manage subjects who manages*/
    public function grade_distributions(){
        return $this->hasMany('App\Grade_distribution','doctor_id');
    }
    /*grades*/
    public function grades()
    {
        return $this->hasMany('App\Grade','doctor_id');
    }
}
