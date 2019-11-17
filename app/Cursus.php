<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cursus extends Model
{
    protected $table = "cursus";
    public $timestamps = false;

    public function utilisateur()
    {
        return $this->belongsTo('App\Utilisateur');
    }

    public function info()
    {
        return Donnees::getCursusInfo($this);
    }
}
