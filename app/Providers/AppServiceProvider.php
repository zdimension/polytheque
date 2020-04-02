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
        if (config("app.force_ssl"))
            \URL::forceScheme('https');

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
