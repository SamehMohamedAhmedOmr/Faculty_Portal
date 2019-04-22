<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_affair extends Model
{
    protected $table = 'student_affairs';
    protected $fillable = [
        'hire_date', 'id'
    ];

    public function user(){
        return $this->morphMany('App\User','userable');
    }
    public function students(){
        return $this->hasMany('App\Student','sa_id');
    }
    public function exam_timetables(){
        return $this->hasMany('App\Exam_timetable','place_id');
    }
}
