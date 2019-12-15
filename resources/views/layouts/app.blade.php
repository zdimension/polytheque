<?php use App\Utilisateur;use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
?>

@section("fullTitle", config('app.name', 'Polythèque') . (View::hasSection("title") ? (" – " . htmlspecialchars_decode(View::getSection("title"), ENT_QUOTES | ENT_HTML5)) : ""))
        
        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="fr-FR">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="all">

    <meta name="author" content="zdimension">
    <meta name="copyright" content="zdimension">

    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="@yield("fullTitle")">
    <meta property="og:type" content="@yield("ogType", "website")">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:image" content="{{asset("images/logo_square.png")}}">
    <meta property="og:site_name" content="Polythèque">
    <meta property="og:description" content="@yield("fullTitle")">

    <meta name="twitter:description" content="Polythèque">
    <meta name="twitter:title" content="@yield("fullTitle")">
    <meta name="twitter:creator" content="@zdimension_">
    <meta name="twitter:card" content="summary">

    <title>@yield("fullTitle")</title>

    <link rel="icon" type="image/png" href="{{asset("images/logo_square.png")}}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
          integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.17.1/themes/prism-okaidia.min.css"
          integrity="sha256-Ykz0nNWK7w4QWJUYR7OraN4773aMB/11aMt1nZyrhuQ=" crossorigin="anonymous"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack("head")
</head>
<body>

<div id="app">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route("root") }}">
            <img src="{{asset("images/logo_white.svg")}}" id="nav-logo" height="40" alt="Logo"/>
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
                    if (auth()->user()->est(Utilisateur::NORMAL))
                    {
                        $liens["cursus.list"] = ["Cursus", "graduation-cap"];
                        $liens["edt.index"] = ["Emploi du temps", "calendar-day"];
                    }

                    if (auth()->user()->est(Utilisateur::AUTEUR))
                    {
                        $liens["article.create"] = ["Créer un article", "pen"];
                    }

                    if (auth()->user()->est(Utilisateur::ADMIN))
                    {
                        //$liens["genres.list"] = ["Gestion des genres", "th"];
                    }
                };

                function is_active($url)
                {
                    return Route::currentRouteName() == $url || Str::startsWith(Route::currentRouteName(), $url . ".");
                }
                function display_link($url, $n, $icon, $get = "")
                {
                ?>
                <li class="nav-item {{is_active($url) ? "active" : ""}}">
                    <a class="nav-link" href="{{route($url).$get}}">@if($icon !== null) <i
                                class="mr-1 fas fa-{{$icon}}"></i> @endif {!!$n!!} <?=is_active($url) ?
                            '<span class="sr-only">(current)</span>' : ""?>
                    </a>
                </li>
                <?php
                }

                foreach ($liens as $url => [$n, $icon])
                    display_link($url, $n, $icon);
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <?php
                    display_link("login", "Connexion", "sign-in-alt", "?redirect=" . url()->current());
                    display_link("register", "Inscription", "user-plus", "?redirect=" . url()->current());
                    ?>
                @else
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle {{is_active("compte") ? "active" : ""}}" href="#"
                           id="navbarDropdown" role="button"
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
    <div class="container-fluid" style="padding-top: 20px; padding-bottom: 20px">
        @yield('content')
    </div>
    <div class="footer-spacer"></div>
    <footer class="footer">
        <div class="container text-center">
            <span class="text-muted" style="line-height: normal">
                Copyright © <a href="https://zdimension.fr">Tom Niget</a> –
                <a href="{{route("alias", ["alias" => "legal"])}}">CGU</a><br/>
                <span style="font-size: 75%;margin-top: 3px;display: block;">
                @if(isGit())
                        <a href="http://github.com/zdimension/polytheque/commit/{{getCurrentCommit()}}">{{substr(getCurrentCommit(), 0, 8)}}</a>
                        –
                    @endif
                page générée en {{ deci(microtime(true) - LARAVEL_START, 4) }} secondes
                </span>
            </span>
        </div>
    </footer>
</div>

<!-- Scripts -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-66960834-2"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-66960834-2');
</script>
<!-- Default Statcounter code for Polythèque
https://poly.zdimension.fr -->
<script type="text/javascript">
    var sc_project = 12148315;
    var sc_invisible = 1;
    var sc_security = "3d4f3781";
</script>
<script type="text/javascript"
        src="https://www.statcounter.com/counter/counter.js"
        async></script>
<noscript>
    <div class="statcounter"><a title="Web Analytics"
                                href="https://statcounter.com/" target="_blank"><img
                    class="statcounter"
                    src="https://c.statcounter.com/12148315/0/3d4f3781/1/"
                    alt="Web Analytics"></a></div>
</noscript>
<!-- End of Statcounter Code -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.17.1/prism.min.js"
        integrity="sha256-HWJnMZHGx7U1jmNfxe4yaQedmpo/mtxWSIXvcJkLIf4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.17.1/components/prism-core.min.js"
        integrity="sha256-Y+Budm2wBEjYjbH0qcJRmLuRBFpXd0VKxl6XhdS4hgA=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.17.1/plugins/autoloader/prism-autoloader.min.js"
        integrity="sha256-ht8ay6ZTPZfuixYB99I5oRpCLsCq7Do2LjEYLwbe+X8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.6/MathJax.js"
        integrity="sha256-nlrDrBTHxJJlDDX22AS33xYI1OJHnGMDhiYMSe2U0e0=" crossorigin="anonymous">
    MathJax.Hub.Config({
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        tex2jax: {
            inlineMath: [['$', '$'], ["\\(", "\\)"]],
            displayMath: [['$$', '$$'], ["\\[", "\\]"]],
            processEscapes: true
        },
        "HTML-CSS": {availableFonts: ["TeX"]}
    });
</script>
<script src="{{ asset("js/app.js") }}"></script>
@stack("foot")
</body>
</html>
