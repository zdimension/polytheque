<?php

namespace App\Providers;

use App\Utilisateur;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::if('respo_vente', function ()
        {
            return auth()->check() && auth()->user()->est(Utilisateur::RESPO_VENTE);
        });

        \Blade::if('respo_comm', function ()
        {
            return auth()->check() && auth()->user()->est(Utilisateur::RESPO_COMM);
        });

        \Blade::if('respo_adh', function ()
        {
            return auth()->check() && auth()->user()->est(Utilisateur::RESPO_ADH);
        });

        \Blade::if('adherent', function ()
        {
            return auth()->check() && auth()->user()->est(Utilisateur::ADHERENT);
        });

        \Blade::if('admin', function ()
        {
            return auth()->check() && auth()->user()->est(Utilisateur::ADMIN);
        });

        \Blade::setEchoFormat('nl2br(e(%s))');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . "/../Helpers/Helpers.php";
    }
}
