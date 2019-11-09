<?php use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;use Illuminate\Support\Str;

?>
        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Polythèque') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
          integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.5.1/leaflet.css"
          integrity="sha256-SHMGCYmST46SoyGgo4YR/9AlK1vf3ff84Aq9yK4hdqM=" crossorigin="anonymous"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/css/star-rating.min.css"
          integrity="sha256-erS4t9C/AvfShlGZwxOG4dnzQuWJhPDPf4xBcYnj+xc=" crossorigin="anonymous"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/themes/krajee-fas/theme.min.css"
          integrity="sha256-+HzjedSWe14YCfXdjzDfnkfkfhNiczN3zJHdC2DmADk=" crossorigin="anonymous"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack("head")
</head>
<body>

<div id="app">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route("root") }}">
            <img src="{{asset("resources/assets/logo_white.svg")}}" id="nav-logo" height="40"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                $liens = [
                    "root" => ["Accueil", "home"]
                ];

                if (auth()->check())
                {
                    if (auth()->user()->est(App\User::ADMIN))
                    {
                        //$liens["genres.list"] = ["Gestion des genres", "th"];
                    }

                    if (auth()->user()->est(App\User::NORMAL))
                    {

                    }
                };

                function is_active($url)
                {
                    return Route::currentRouteName() == $url || Str::startsWith(Route::currentRouteName(), $url . ".");
                }
                function display_link($url, $n, $icon, $get="")
                {
                    ?>
                    <li class="nav-item {{is_active($url) ? "active" : ""}}">
                    <a class="nav-link" href="{{route($url).$get}}">@if($icon !== null) <i
                                class="mr-1 fas fa-{{$icon}}"></i> @endif {!!$n!!} <?=is_active($url) ? '<span class="sr-only">(current)</span>' : ""?>
                    </a>
                </li>
                <?php
                }
                ?>
                @foreach($liens as $url => [$n, $icon])
                    <?php display_link($url, $n, $icon); ?>
                @endforeach
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <?php
                    display_link("login", "Connexion", "sign-in-alt", "?redirect=" . url()->current());
                    display_link("register", "Inscription", "user-plus", "?redirect=" . url()->current());
                    ?>
                @else
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle {{is_active("compte") ? "active" : ""}}" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->nomAffichage() }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <h6 class="dropdown-header">{{ Auth::user()->roleAffichage() }}</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item pl-3" href="{{ route('compte.view') }}"><i
                                        class="mr-1 fas fa-user"></i> Mon compte</a>
                            <a class="dropdown-item pl-3" href="{{ route('logout') }}?redirect={{url()->current()}}"><i
                                        class="mr-1 fas fa-sign-out-alt"></i> Déconnexion</a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
    <div class="container-fluid" style="padding-top: 20px">
        @yield('content')
    </div>
</div>
<!-- Scripts -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.5.1/leaflet.js"
        integrity="sha256-EErZamuLefUnbMBQbsEqu1USa+btR2oIlCpBJbyD4/g=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/star-rating.min.js"
        integrity="sha256-TX3InxvStdA3RUVQNAd3B6GW0P8aJPHmGoDxlwcAr98=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/locales/fr.js"
        integrity="sha256-8kugmo5zrICka3UPXh0oyiaqCUGKRO9oVaUM3lNIMGc=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/themes/krajee-fas/theme.min.js"
        integrity="sha256-2ZLo+r+uunQnJNFwHWSurqLFvLbcZxN4BkHZ+bBl3tg=" crossorigin="anonymous"></script>
<script src="{{ asset("js/app.js") }}"></script>
@stack("foot")
</body>
</html>
