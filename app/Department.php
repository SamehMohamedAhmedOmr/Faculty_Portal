<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name', 'description', 'admin_id'
    ];

    public function admin(){
        return $this->belongsTo('App\Admin');
    }

    public function subjects(){
        return $this->hasMany('App\Subject','department_id');
    }

    public function students(){
        return $this->hasMany('App\Student','department_id');
    }
}
