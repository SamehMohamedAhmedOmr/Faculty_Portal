<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'Doc'=>'App\Doctor',
    'T_A'=>'App\Teacher_assistant'
]);

class Timetable extends Model
{
    //
    protected $table = 'timetables';
    protected $fillable = [
        'timetableable_id', 'timetableable_type', 'semester_id', 'subject_id','admin_id','place_id', 'day', 'time'
    ];
    public function timetableable ()
    {
        return $this->morphTo();
    }
    public function semester(){
        return $this->belongsTo('App\Semester');
    }
    public function subject(){
        return $this->belongsTo('App\Open_course','subject_id','subject_id');
    }
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    public function place(){
        return $this->belongsTo('App\Place');
    }
    public function students(){
        return $this->belongsToMany(Student::class,'student_timetable');
    }
}