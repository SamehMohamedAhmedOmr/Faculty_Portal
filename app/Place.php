<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = 'places';
    protected $fillable = [
        'name', 'seats', 'type', 'admin_id'
    ];

    public function admin(){
        return $this->belongsTo('App\Admin');
    }

    public function timetables(){
        return $this->hasMany('App\Timetable','place_id');
    }
    public function exam_timetables(){
        return $this->hasMany('App\Exam_timetable','place_id');
    }
}
