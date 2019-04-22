<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';
    protected $fillable = [
        'student_id', 'instructor_id', 'semester_id', 'subject_id', 'section', 'quiz', 'final', 'midterm', 'participation', 'attendance', 'project', 'assignment'
    ];

    public function student(){
        return $this->belongsTo('App\Student');
    }
    public function semester(){
        return $this->belongsTo('App\Semester');
    }
    public function subject(){
        return $this->belongsTo('App\Subject');
    }
    /*grade belongs to one doctor and one subject*/
    public function doctor()
    {
        return$this->belongsTo('App\Doctor','doctor_id','id');
    }
    public function instructor()
    {
        return$this->belongsTo('App\Teacher_assistant','instructor_id','id');
    }

}
