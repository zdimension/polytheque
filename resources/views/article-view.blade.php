@extends('layouts.app')

@section('content')
    <div class='card bg-light w-100'>
        <div class="card-header card-hf-divided">
            <div class="card-hf-column">
                <h4 class="mb-0 mx-2">{{$art->titre}}</h4>
            </div>
            <div class="card-hf-column">
                par {{$art->auteur->nom}}
            </div>
            <div class="card-hf-column">
                le {{$art->date_creation->format("d/m/Y")}}
            </div>
            <div class="card-hf-column">
                <a class="btn btn-primary" href="{{route("article.edit", ["art" => $art])}}"><i class="fa fa-pen mr-1" aria-hidden="true"></i> Modifier</a>
            </div>
            <div class="card-hf-column">
                <a class="btn btn-danger" href="{{route("article.delete", ["art" => $art])}}"><i class="fa fa-trash mr-1" aria-hidden="true"></i> Supprimer</a>
            </div>
        </div>
        <div class="card-body">
            {{$art->contenu}}
        </div>
    </div>
@endsection