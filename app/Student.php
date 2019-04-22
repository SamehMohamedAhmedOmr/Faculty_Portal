<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'id', 'account_status', 'graduated_status', 'department_id', 'sa_id'
    ];

    public function user(){
        return $this->morphMany('App\User','userable');
    }
    public function sa(){
        return $this->belongsTo('App\Student_affair');
    }
    public function department(){
        return $this->belongsTo('App\Department');
    }
    public function grades(){
        return $this->hasMany('App\Grade','student_id');
    }
    public function rates(){
        return $this->belongsToMany('App\Subject','student_rate','','subject_id');
    }
    public function exam_timetables(){
        return $this->belongsToMany('App\Exam_timetable');
    }
    public function timetables(){
        return $this->belongsToMany(Timetable::class,'student_timetable');
    }
}
