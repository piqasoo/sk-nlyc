<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $table = 'tags';

    /*
    ** Have Many Articles
    ** @return Illuminate\Database\Eloquent\Model
    */
    public function Article(){
        return $this->belongsToMany(Article::class);
    }

}
