<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Utilisateur extends Authenticatable
{
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

    const ADMIN = 0;
    const NORMAL = 10;

    use Notifiable;

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
}
