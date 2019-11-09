@extends('layouts.app')

@section('title', 'Genres')


@section("content")
    <div class="row">
        <div class="col-auto">
            <div class='card bg-light' style="min-width: 400px;">
                <div class="card-header">
                    <h4 class="mb-0">Ajouter un genre</h4>
                </div>
                <div class="card-body">
                    <form action="{{route("genres.add")}}" method="post">
                        {{ method_field("PUT") }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="search" class="form-control form-control-sm"
                                   id="nom" name="nom" required maxlength="50" value="{{old("nom")}}"/>
                        </div>
                        @include("widgets.field-error", ["field" => "nom"])
                        <div class="form-row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-block">Ajouter</button>
                            </div>
                            <div class="col">
                                <a href="{{route("genres.list")}}" class="btn btn-outline-secondary btn-block">Effacer</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <h2 class="mb-3">Les genres</h2>
            @if($genres->isEmpty())
                <h5 class="mt-3">Vous n'avez créé aucun genre.</h5>
            @else
            <ul class="list-group">
                @foreach($genres as $g)
                    <li class="list-group-item">{{$g->gen_libelle}}</li>
                @endforeach
            </ul>
                @endif
        </div>
    </div>
@endsection












