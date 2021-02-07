<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $table = 'tags';


    protected $appends  = ['article_count'];

    /*
    ** Have Many Articles
    ** @return Illuminate\Database\Eloquent\Model
    */
    public function Articles(){
        return $this->belongsToMany(Article::class);
    }

    /*
    ** Get Attribute
    ** @return article_count
    */
    public function getArticleCountAttribute(){
        return $this->Articles()->get()->count();
    }
}
