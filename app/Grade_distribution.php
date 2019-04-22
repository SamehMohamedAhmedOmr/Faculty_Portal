<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade_distribution extends Model
{
    protected $table = 'grade_distributions';
    protected $fillable = [
        'semester_id', 'subject_id', 'doctor_id', 'section', 'quiz_grade', 'midterm', 'participation', 'attendance', 'project', 'assignment'
    ];
    protected $primaryKey = ['semester_id', 'subject_id','doctor_id'];
    public $incrementing = false;

    public function semester(){
        return $this->belongsTo('App\Semester');
    }
    public function subject(){
        return $this->belongsTo('App\Subject');
    }
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }
}
