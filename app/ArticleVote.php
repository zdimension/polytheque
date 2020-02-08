<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleVote extends Model
{
    public $timestamps = false;

    public function utilisateur()
    {
        return $this->belongsTo('App\Utilisateur');
    }

    public function article()
    {
        return $this->belongsTo('App\Article');
    }
}
