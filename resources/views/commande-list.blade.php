@extends('layouts.app')

@section('title', 'Commandes')

@section("content")
    <div class="row">
        <div class="col">
            <h2 class="mb-3">Commandes passées</h2>
            @if($comms->isEmpty())
                <h5 class="mt-3">Aucune commande à afficher.</h5>
            @else
                @foreach($comms as $c)
                    <div class="card mb-3">
                        <div class="card-header card-hf-divided">
                            <div class="card-hf-column">
                                Commande du&nbsp;<strong>{{$c->com_date}}</strong>
                            </div>
                            <div class="card-hf-column">
                                <strong>{{money($c->montant)}}</strong>
                            </div>
                            @respo_adh
                            <div class="card-hf-column">
                                Client :&nbsp;<strong>{{$c->auteur->compte->nomAffichage()}}</strong>
                            </div>
                            @endrespo_adh
                        </div>
                        <div class="card-body card-body-row">
                            <div class="col-auto card-body-column">
                                Livraison à
                                    @switch($c->typeLivraison)
                                        @case(0)
                                    <strong>{{$c->relais->rel_nom}}</strong>
                                <hr>
                                    {{$c->relais->affichageAdresse()}}
                                        @break;
                                        @case(1)
                                    <strong>{{$c->adresse->adr_nom}}</strong>
                                <hr>
                                        {{$c->adresse->affichage()}}
                                        @break
                                        @case(2)
                                    <strong>{{$c->magasin->mag_nom}}</strong>
                                <hr>
                                {{$c->magasin->mag_ville}}
                                        @break
                                    @endswitch
                                </strong>
                            </div>
                            <div class="col card-body-column">
                                <table class="table table-hover table-thin-padding table-no-border mb-0">
                                    <tbody>
                                    @foreach($c->lignes as $l)
                                        <tr>

                                            <td class="col">{{$l->livre->liv_titre}}</td>
                                            <td class="col text-right">{{$l->lec_quantite}}&times;</td>
                                            <td class="col text-right">{{money($l->livre->liv_prixttc)}}</td>
                                            <td class="col font-weight-bold text-right">{{money($l->montant)}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="col font-weight-bold">Total</td>
                                        <td class="col"></td>
                                        <td class="col"></td>
                                        <td class="col font-weight-bold text-right">{{money($c->montant)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

            @endif
        </div>
    </div>
@endsection












