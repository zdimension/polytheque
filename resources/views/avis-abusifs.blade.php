@extends('layouts.app')

@section('title', 'Commentaires signalés')

@section("content")
    <div class="row">
        <div class="col">
            <h2 class="mb-3">Commentaires signalés</h2>
            @if($comms->isEmpty())
                <h5 class="mt-3">Aucun commentaire signalé.</h5>
            @else
                @foreach($comms as $comm)
                    @include("widgets.avis", ["delRedirect" => url()->current(), "affScore" => true])
                @endforeach
            @endif
        </div>
    </div>
@endsection












