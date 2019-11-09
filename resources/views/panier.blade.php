@extends('layouts.app')

@section('title', 'Panier')

@section("content")
    <div class="row">
        <div class="col-auto">
            <div class='card bg-light' style="width: 400px;">
                <div class="card-header">
                    <h4 class="mb-0">Panier</h4>
                </div>
                <div class="card-body">
                    <table class="table table-thin-padding table-no-border">
                        <tbody>
                        @foreach($panier as [$livre, $qty])
                            <tr>
                                <td>{{$qty}}&times;</td>
                                <td>{{$livre->liv_titre}}</td>
                                <td class="font-weight-bold text-right">{{money($livre->liv_prixttc * $qty)}}</td>
                                <td>
                                    <form action="{{route("panier.delete", ["liv_id" => $livre->liv_id])}}"
                                          method="post"
                                          class="col-sm-1 my-auto p-0">
                                        {{ method_field("DELETE") }}
                                        {{ csrf_field() }}
                                        <button id="btnSubmit" type="submit" class="btn btn-sm btn-danger"><i
                                                    class="fa fa-times"
                                                    aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td class="font-weight-bold">Total</td>
                            <td class="font-weight-bold text-right">{{money(array_sum(array_map(function($l) { return $l[0]->liv_prixttc * $l[1];}, $panier)))}}</td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <form action="{{route("panier.order")}}" method="post">
                        {{csrf_field()}}
                        <div class="form-row form-group{{ $errors->has('liv_type') ? ' has-error' : '' }}">
                            <div class="col-auto">
                                <label class="mt-1" for="adr_type">Mode de livraison :</label>
                            </div>
                            <div class="col">
                                <select name="liv_type" id="liv_type" onchange="this.form.submit()"
                                        class="form-control {{ $errors->has('liv_type') ? ' is-invalid' : '' }}">
                                    <option value="" selected></option>
                                    <?php
                                    $typ = [
                                        "Point relais",
                                        "Adresse enregistrée",
                                        "Retrait en magasin"
                                    ];
                                    ?>
                                    @foreach($typ as $i => $c)
                                        <option
                                                value="{{$i}}" {{session()->get("liv_type")!== null && session()->get("liv_type")== $i?"selected":""}}>{{$c}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (session()->get("liv_type") !== null)
                            <div class="form-row form-group{{ $errors->has('liv_cible') ? ' has-error' : '' }}">
                                <div class="col-auto">
                                    <label class="mt-1" for="liv_cible">{{["Point relais", "Adresse", "Magasin"][session()->get("liv_type")]}} :</label>
                                </div>
                                <div class="col">
                                    <select name="liv_cible" id="liv_cible"
                                            class="form-control {{ $errors->has('liv_cible') ? ' is-invalid' : '' }}">
                                        <option value="" selected disabled></option>
                                        <?php
                                            $nom = ["rel", "adr", "mag"][session()->get("liv_type")] . "_nom";
                                            $default = null;
                                            if (session()->get("liv_type") == 1)
                                                $default = @(auth()->user()->adherent->adresses->firstWhere("adr_type", "Livraison")->adr_id);
                                            else if (session()->get("liv_type") == 2)
                                                $default = auth()->user()->adherent->magasin->mag_id;
                                        ?>
                                        @foreach($cibles as $c)
                                            <option
                                                    value="{{$c->getKey()}}" {{old("liv_cible", $default)===$c->getKey()?"selected":""}}>{{$c->$nom}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            @include("widgets.field-error", ["field" => "liv_cible"])
                        @endif
                        <button id="btnOrder" name="order" @if(!$panier || session()->get("liv_type") === null) disabled
                                @endif type="submit"
                                class="btn btn-primary btn-block"><i class="mr-1 fas fa-cart-arrow-down"></i> Commander
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            @include("widgets.field-error", ["field" => "qty", "classes" => "mt-0"])
            <h2 class="mb-3">Contenu du panier</h2>
            @if(!$panier)
                <h5 class="mt-3">Votre panier est vide.</h5>
            @else
                <ul class="list-group">
                    @foreach($panier as [$livre, $qty])
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-auto d-flex pr-0">
                                    <form action="{{route("panier.delete", ["liv_id" => $livre->liv_id])}}"
                                          method="post" class="my-auto">
                                        {{ method_field("DELETE") }}
                                        {{ csrf_field() }}
                                        <button id="btnSubmit" type="submit" class="btn btn-danger"><i
                                                    class="fa fa-times"
                                                    aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="ml-3">
                                    <a href="{{route("livres.detail", ["id" => $livre->liv_id])}}">@include("widgets.livre-img")</a>
                                </div>
                                <div class="col">
                                    <h5><a style="color: black"
                                           href="{{route("livres.detail", ["id" => $livre->liv_id])}}">{{$livre->liv_titre}}</a>
                                    </h5>
                                    par {!!$livre->auteurs->map(function (App\Auteur $aut)
                                {
                                    return '<a href="' . route("livres.list") . '?auteur=' . $aut->aut_nom . '">' .
                                        $aut->aut_nom . '</a>';
                                })->implode(", ") ?: "aucun"!!} &mdash;

                                    prix unitaire : {{money($livre->liv_prixttc)}}

                                    <form action="{{route("panier.quantite", ["id" => $loop->index])}}"
                                          method="post" class="mt-2">
                                        {{ method_field("PATCH") }}
                                        {{ csrf_field() }}
                                        <div class="form-row form-group mb-2">
                                            <div class="col-auto">
                                                <input type="number" class="w-10 form-control form-control-sm"
                                                       name="qty" style="width: 80px" required value="{{$qty}}"/>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-sm btn-primary"><i
                                                            class="fa fa-check"
                                                            aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <strong>{{money($livre->liv_prixttc * $qty)}}</strong>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection












