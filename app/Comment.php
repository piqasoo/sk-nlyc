<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = 'comments';
    protected $hidden = ['pivot'];

    /*
    ** Have Many Articles
    ** @return Illuminate\Database\Eloquent\Model
    */
    public function Articles(){
        return $this->belongsToMany(Article::class);
    }
}
