<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $table = 'mails';
    protected $fillable = [
        'sender_id', 'receiver_id', 'description', 'header', 'date_time'
    ];

    public function sender(){
        return $this->belongsTo('App\User','sender_id','userable_id');
    }
    public function receiver(){
        return $this->belongsTo('App\User','receiver_id','userable_id');
    }

}
