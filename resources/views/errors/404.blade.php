@extends('layouts.app')

@section('title', '404')


@section("content")
    <div class="w-100 text-center">
        <img class="pb-3" src="https://media.giphy.com/media/hEc4k5pN17GZq/giphy.gif"/>

        <h2>La page demandée n'a pas pu être trouvée.</h2>

        <a class="pt-2 btn btn-primary" href="{{route("root")}}">Retour à l'accueil</a>
    </div>
@endsection












