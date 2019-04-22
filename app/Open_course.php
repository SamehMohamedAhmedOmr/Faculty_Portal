<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Open_course extends Model
{
    protected $table = 'open_courses';
    protected $fillable = [
        'semester_id', 'subject_id', 'admin_id', 'doctor_id','num_dr','num_ta'
    ];

    public function semester(){
        return $this->belongsTo('App\Semester');
    }
    public function subject(){
        return $this->belongsTo('App\Subject');
    }
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }
    public function timetables(){
        return $this->hasMany('App\Timetable','subject_id');
    }
    public function exam_timetables(){
        return $this->hasMany('App\Exam_timetable','subject_id');
    }
}
