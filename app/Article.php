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

    public function votes()
    {
        return $this->hasMany('App\ArticleVote');
    }

    protected $appends = ["score"];

    public function getScoreAttribute()
    {
        // borne inférieure de l'intervalle de confiance de Wilson pour un paramètre de Bernoulli
        // en gros ça donne un score relatif de l'avis
        // score élevé = avis utile, score bas = avis peu utile
        // c'est plus fiable que de juste faire OUI - NON ou OUI / (OUI + NON)
        $pos = $this->votes->where("positif", 1)->count();
        $neg = $this->votes->where("positif", 0)->count();
        $tot = $pos + $neg;
        if ($tot == 0) return 0;
        return (($pos + 1.9208) / $tot -
                1.96 * sqrt(($pos * $neg) / $tot + 0.9604) /
                $tot) / (1 + 3.8416 / $tot);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($user) {
            $user->votes()->delete();
        });
    }
}
