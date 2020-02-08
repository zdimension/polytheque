@extends('layouts.app')

@section("title", "Accueil")

@push("head")
    <style>
        a.article {
            display: flex;
            flex-direction: row;
        }

        a.article > span:first-child {
            flex-grow: 1;
            font-weight: bold;
            font-size: 125%;
        }

        a.article > span:last-child {
            align-self: center;
            font-size: 80%;
        }
    </style>
@endpush

@section('content')
    <h2 class="mb-3">Accueil</h2>

    <h6>Tutos et trucs utiles en tout genre. Lisez bien les <a href="{{route("alias", ["alias" => "legal"])}}">CGU</a>
    </h6>

    <hr>
    <h4 class="mb-3">Derniers articles</h4>

    <ul class="list-group">
        @forelse(\App\Article::where("invisible", 0)->orderByDesc("date_creation")->get() as $art)
            <a class="list-group-item list-group-item-action article"
               href="{{route("article.view", ["art" => $art])}}"><span>{{$art->titre}}</span><span>{{$art->date_creation->format("d/m/Y")}}</span></a>
        @empty
            Aucun article pour le moment.
        @endforelse
    </ul>
@endsection
