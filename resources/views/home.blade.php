@extends('layouts.app')

@section('content')
    <h2 class="mb-3">Accueil</h2>

    <h6>Pas grand chose à voir ici pour l'instant. Lisez bien les <a href="{{route("legal")}}">CGU</a></h6>

    <hr>
    <h4 class="mb-3">Derniers articles</h4>

    <ul class="list-group">
        @forelse(\App\Article::all()->sortByDesc("date_creation") as $art)
            <li class="list-group-item">$art->titre</li>
        @empty
            Aucun article pour le moment.
        @endforelse
    </ul>
@endsection
