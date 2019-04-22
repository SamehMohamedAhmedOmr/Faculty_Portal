<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $fillable = [
        'name', 'description', 'final_grade', 'level_req', 'practical', 'credit_hours', 'prerequisite', 'admin_id', 'department_id'
    ];

    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    public function students(){
        return $this->belongsToMany('App\Student','student_subject');
    }
    public function department(){
        return $this->belongsTo('App\Department');
    }
    public function grades(){
        return $this->hasOne('App\Grade','subject_id');
    }
    public function open_courses(){
        return $this->hasMany('App\Open_course','subject_id');
    }
    public function materials(){
        return $this->hasMany('App\Material','subject_id');
    }
    public function timetables(){
        return $this->hasMany('App\Timetable','subject_id');
    }
    public function rates(){
        return $this->belongsToMany('App\Student','student_rate','subject_id','student_id');
    }
    public function grade_distributions(){
        return $this->hasMany('App\Grade_distribution','subject_id');
    }

}
