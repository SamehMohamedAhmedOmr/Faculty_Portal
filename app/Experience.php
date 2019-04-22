<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table = 'experiences';
    protected $fillable = [
        'experienceable_id', 'experienceable_type', 'date', 'subject',	'description'
    ];

    public function experienceable ()
    {
        return $this->morphTo();
    }
}
