@extends('layouts.app')

@section('title', 'Livre')

@section('sidebar')
    @parent
@endsection

@section('content')

    @if ($livre === null)
        <h2>Livre inexistant</h2>
    @else
        <div class="card mb-1">
            <div class="card-header">
                <h2 class="mb-0">{{$livre->liv_titre}}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-auto" style="width: auto;">
                        @if ($livre->photos->isEmpty())
                            <div style="display:inline-block;">
                                <div
                                        style="padding: 2px;width:180px;height:290px;border:1px dashed #bbb;
                                        display: flex;
                                        align-items: center;">
                                    <div style="width: 100%; text-align: center;">Pas d'image disponible</div>
                                </div>
                            </div>
                        @else
                            @if($livre->photos->count() > 1)
                                <a class="carousel-control-prev car-control-dark" href="#carouselLivre" role="button"
                                   data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Précédent</span>
                                </a>
                            @endif
                            <div id="carouselLivre" class="carousel slide" data-ride="carousel"
                                 style="width:180px;height:290px;{{$livre->photos->count() > 1 ? "margin: 0 20px;" : ""}}">
                            <!--@if($livre->photos->count() > 1)
                                <ol class="carousel-indicators">
@foreach($livre->photos as $photo)
                                    <li data-target="#carouselLivre" data-slide-to="{{$loop->index}}" {{$loop->first ? 'class="active"' : ""}}></li>
                    @endforeach
                                        </ol>
                                        @endif-->
                                    <div class="carousel-inner">
                                        @foreach($livre->photos as $photo)
                                            <div class="card frame carousel-item {{$loop->first ? "active" : ""}}">
                                                <img class="d-block w-100 livreimg"
                                                     src="{{$photo->url()}}">
                                            </div>
                                        @endforeach
                                    </div>
                            </div>
                            @if($livre->photos->count() > 1)
                                <a class="carousel-control-next car-control-dark" href="#carouselLivre" role="button"
                                   data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Suivant</span>
                                </a>
                            @endif
                        @endif
                        @respo_vente
                        <form action="{{route("livres.photos.add", ["redirect" => $livre->liv_id])}}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field("PUT") }}
                            <div class="pt-3">
                                <div class="form-group mb-2" style="width:{{$livre->photos->count() > 1?220:180}}px">
                                    <div class="custom-file text-left mt-2">
                                        <input type="file" class="custom-file-input" id="upload{{$livre->liv_id}}"
                                               name="{{$livre->liv_id}}"
                                               accept=".jpg,.jpeg,.gif,.png">
                                        <label class="custom-file-label"
                                               id="upload{{$livre->liv_id}}"><span></span></label>
                                    </div>
                                </div>
                                <button type="submit" disabled class="btn btn-primary btn-block pb-2"><i class="mr-1 fas fa-cloud-upload-alt"></i> Envoyer la photo
                                </button>
                                <button type="reset" class="btn btn-outline-secondary btn-block"><i class="mr-1 fas fa-eraser"></i> Réinitialiser</button>
                            </div>
                        </form>
                        @endrespo_vente
                    </div>
                    <div class="col-lg-auto" style="width: auto;">
                        <ul class="list-unstyled">
                            <?php
                            $cols = [
                                "Auteur(s)" => $livre->auteurs->map(function (App\Auteur $aut)
                                {
                                    return '<a href="' . route("livres.list") . '?auteur=' . $aut->aut_nom . '">' .
                                        $aut->aut_nom . '</a>';
                                })->implode(", ") ?: "aucun",
                                "Genre" => '<a href="' . route("livres.list") . '?genre=' . $livre->gen_id . '">' .
                                    $livre->genre->gen_libelle . '</a>',
                                "Éditeur" => $livre->editeur->edi_nom,
                                "Rubrique(s)" => $livre->rubriques->map(function (App\Rubrique $rub)
                                {
                                    return '<a href="' . route("livres.list") . '?rubrique=' . $rub->rub_id . '">' .
                                        $rub->rub_libelle . '</a>';
                                })->implode(", ") ?: "aucune",
                                "Parution" => $livre->liv_dateparution,
                                "Prix" => money($livre->liv_prixttc),
                                "Stock" => $livre->liv_stock
                            ];
                            foreach ($cols as $titre => $val)
                            {
                                echo "<li>";
                                echo "<strong>" . $titre . " :</strong> " . $val;
                                echo "</li>";
                            }
                            ?>
                        </ul>
                        @adherent
                        @include("widgets.livre-ajout-panier")
                        @endadherent
                    </div>
                    <div class="col">
                        <div class="card mb-1">
                            <div class="card-header">
                                <strong class="mb-0">Histoire</strong>
                            </div>
                            <div class="card-body">
                                {{$livre->liv_histoire}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @adherent
        <div class="card mb-1" id="deposer-comm">
            <div class="card-header">
                <h4 class="mb-0" style="display: inline-block">Déposer un commentaire</h4>
            </div>
            <div class="card-body">
                @if ($existant = $livre->avis->first(function($a) { return $a->auteur == auth()->user()->adherent; }))
                    <h6 class="m-0">Vous avez déjà déposé <a href="#comm{{$existant->avi_id}}">un commentaire</a> sur
                        cet article.</h6>
                @elseif (!auth()->user()->adherent->livresCommandes->contains($livre))
                    <h6 class="m-0">Vous devez avoir acheté le livre pour déposer un commentaire.</h6>
                @else
                    <form action="{{action("AvisController@add", ["liv_id" => $livre->liv_id])}}" method="post">
                        {{csrf_field()}}
                        {{method_field("PUT")}}
                        <div class="form-group{{ $errors->has('avi_titre') ? ' has-error' : '' }}">
                            <input id="avi_titre" type="text" class="form-control" name="avi_titre" placeholder="Titre"
                                   value="{{ old('avi_titre') }}" required maxlength="100">

                            @if ($errors->has('avi_titre'))
                                <div class="alert alert-danger mt-3" role="alert">
                                    {{ $errors->first('avi_titre') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('avi_detail') ? ' has-error' : '' }}">
                        <textarea id="avi_detail" type="text" class="form-control" name="avi_detail"
                                  placeholder="Commentaire"
                                  value="{{ old('avi_detail') }}" required rows="4" maxlength="2000"></textarea>

                            @if ($errors->has('avi_detail'))
                                <div class="alert alert-danger mt-3" role="alert">
                                    {{ $errors->first('avi_detail') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group row mb-0{{ $errors->has('avi_note') ? ' has-error' : '' }}">
                            <label for="avi_note" class="col-auto col-form-label mt-1">Note : </label>
                            <div class="col-auto px-0">
                                <input class="form-control rating-star rating-loading"
                                       data-size="sm" type="number" name="avi_note" id="avi_note" required
                                       value="{{old('avi_note')}}"/>
                            </div>
                            <div class="flex-grow-1"></div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary"><i class="mr-1 fas fa-paper-plane"></i> Envoyer</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        @endadherent
        <div class="card mb-1">
            <div class="card-header">
                <h4 class="mb-0" style="display: inline-block">Commentaires</h4>
                <form action="{{url()->full()}}#commentaires" method="get" style="display: inline-block;" class="pl-2">
                    <select name="tricomm" id="tricomm" class="form-control form-control-sm"
                            onchange="this.form.submit()">
                        <?php
                        $modes = [
                            "Les plus utiles d'abord",
                            "Les moins utiles d'abord",
                            "Les plus récents d'abord",
                            "Les plus anciens d'abord",
                            "Les mieux notés d'abord",
                            "Les moins bien notés d'abord",
                        ];
                        $current = request("tricomm");
                        if (empty($current))
                            $current = 0;
                        ?>
                        @foreach($modes as $i => $nom)
                            <option value="{{$i}}" {{$current==$i?"selected":""}}>{{$nom}}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="card-body" id="commentaires">
                @if ($livre->avis->isEmpty())
                    <h6>Pas de commentaires pour le moment.</h6>
                @else
                    <?php
                    $coll = $livre->avis;

                    if ($current == 0) $coll = $coll->sortByDesc("score");
                    if ($current == 1) $coll = $coll->sortBy("score");
                    if ($current == 2) $coll = $coll->sortByDesc("avi_date");
                    if ($current == 3) $coll = $coll->sortBy("avi_date");
                    if ($current == 4) $coll = $coll->sortByDesc("avi_note");
                    if ($current == 5) $coll = $coll->sortBy("avi_note");

                    ?>
                    @foreach($coll as $comm)
                        @include("widgets.avis")
                    @endforeach
                @endif
            </div>
        </div>

    @endif
@endsection
