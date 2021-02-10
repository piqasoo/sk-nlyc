<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $table = 'articles';
    protected $hidden = ['pivot'];

    /*
    ** Have Many Comments
    ** @return Illuminate\Database\Eloquent\Model
    */
    public function Comment(){
        return $this->belongsToMany(Comment::class);
    }

    /*
    ** Have Many Comments
    ** @return Illuminate\Database\Eloquent\Model
    */
    public function Tag(){
        return $this->belongsToMany(Tag::class);
    }

}
