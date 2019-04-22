<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam_timetable extends Model
{
    //
    protected $table = 'exam_timetables';
    protected $fillable = [
        'semester_id', 'subject_id','sa_id','place_id',
        'duration', 'day', 'time', 'ta_id','type','date'
    ];

    public function semester(){
        return $this->belongsTo('App\Semester');
    }
    public function subject(){
        return $this->belongsTo('App\Open_course','subject_id');
    }
    public function sa(){
        return $this->belongsTo('App\Admin');
    }
    public function ta(){
        return $this->belongsTo('App\Teacher_assistant');
    }
    public function place(){
        return $this->belongsTo('App\Place');
    }
    public function students(){
        return $this->belongsToMany('App\Student');
    }

}
