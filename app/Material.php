<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';
    protected $fillable = [
        'semester_id', 'subject_id', 'date', 'doctor_id','file','description'
    ];
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
