<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $table = 'articles';
    protected $hidden = ['pivot'];
    protected $appends  = ['comment_count'];

    /*
    ** Have Many Comments
    ** @return Illuminate\Database\Eloquent\Model
    */
    public function Comments(){
        return $this->belongsToMany(Comment::class);
    }

    /*
    ** Have Many Comments
    ** @return Illuminate\Database\Eloquent\Model
    */
    public function Tags(){
        return $this->belongsToMany(Tag::class);
    }


    /*
    ** Get Attribute
    ** @return comment_count
    */
    public function getCommentCountAttribute(){
        return $this->Comments()->get()->count();
    }
}
