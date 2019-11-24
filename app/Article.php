<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

    public function auteur()
    {
        return $this->belongsTo('App\Utilisateur');
    }
}
