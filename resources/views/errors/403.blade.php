@extends('layouts.app')

@section('title', '403')


@section("content")
    <div class="w-100 text-center">
        <img class="pb-3" src="http://giphygifs.s3.amazonaws.com/media/njYrp176NQsHS/giphy.gif"/>

        <h2>Vous n'avez pas l'autorisation d'accéder à cette page.</h2>

        <a class="pt-2 btn btn-primary" href="{{route("root")}}">Retour à l'accueil</a>
    </div>
@endsection












