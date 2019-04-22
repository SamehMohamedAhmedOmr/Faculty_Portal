<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlbumImage extends Model
{
    protected $table = 'album_images';

    protected $fillable = ['id','album_id', 'filename'];

    public function album()
    {
        return $this->belongsTo('App\Album');
    }
}
