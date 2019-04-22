<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semesters';
    protected $fillable = [
        'name','isSummer','start_date', 'end_date', 'final_monitoring_grades_date', 'midterm_week', 'open_register_date', 'end_register_date', 'complete', 'admin_id'
    ];

    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    public function grades(){
        return $this->hasMany('App\Grade','semester_id');
    }
    public function timetables(){
        return $this->hasMany('App\Timetable','semester_id');
    }
    public function exam_timetables(){
        return $this->hasMany('App\Exam_timetable','semester_id');
    }
    public function open_courses(){
        return $this->hasMany('App\Open_course','semester_id');
    }
    public function materials(){
        return $this->hasMany('App\Material','semester_id');
    }
    public function grade_distributions(){
        return $this->hasMany('App\Grade_distribution','semester_id');
    }
}
