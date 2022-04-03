<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    //
    protected $table = 'article_type';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
