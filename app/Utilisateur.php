<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class Utilisateur extends Authenticatable implements MustVerifyEmail
{
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

    const ADMIN = 0;
    const AUTEUR = 8;
    const NORMAL = 10;

    use Notifiable;

    use Eloquence, Mappable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'email', 'mdp', 'privileges'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'mdp', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_creation' => 'datetime',
        'date_modification' => 'datetime',
        'colorations' => 'array'
    ];

    protected $maps = [
        'email_verified_at' => 'date_verification'
    ];

    public function est($type)
    {
        return $this->privileges <= $type;
    }

    public function nomAffichage()
    {
        return $this->nom;
    }

    public function roleAffichage()
    {
        return [
            self::ADMIN => "Administrateur",
            self::NORMAL => "Polypote"
        ][$this->privileges];
    }

    public function getAuthPassword()
    {
        return $this->mdp;
    }

    public function cursus()
    {
        return $this->hasMany('App\Cursus');
    }

    public function votes()
    {
        return $this->hasMany('App\ArticleVote');
    }
}
