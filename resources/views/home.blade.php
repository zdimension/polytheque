@extends('layouts.app')

@section("title", "Accueil")

@section('content')
    <h2 class="mb-3">Accueil</h2>

    <h6>Pas grand chose à voir ici pour l'instant. Lisez bien les <a href="{{route("legal")}}">CGU</a></h6>

    <hr>
    <h4 class="mb-3">Derniers articles</h4>

    <ul class="list-group">
        @forelse(\App\Article::where("invisible", 0)->orderByDesc("date_creation")->get() as $art)
            <a class="list-group-item list-group-item-action" style="font-weight: bold; font-size: 125%"
               href="{{route("article.view", ["art" => $art])}}">{{$art->titre}}</a>
        @empty
            Aucun article pour le moment.
        @endforelse
    </ul>
@endsection
