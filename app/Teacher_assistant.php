<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher_assistant extends Model
{
    protected $table = 'teacher_assistants';
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
    public function exam_timetables(){
        return $this->hasMany('App\Exam_timetable','place_id');
    }
    /*grades*/
    public function grades()
    {
        return $this->hasMany('App\Grade','instructor_id');
    }
}
