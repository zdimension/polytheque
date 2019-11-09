@extends('layouts.app')

@section('title', 'Livre')


@section("content")
    <div class="row">
        <div class="col-auto">
            <div class='card w-25 bg-light' style="min-width: 350px;">
                <div class="card-header">
                    <h4 class="mb-0">Recherche de livres</h4>
                </div>
                <div class="card-body">
                    <form action="{{action("LivreController@index")}}" method="get" role="search">
                        <div class="form-group">
                            <label for="auteur">Auteur</label>
                            <input type="search" class="form-control form-control-sm"
                                   id="auteur" name="auteur" value="{{request("auteur")}}"/>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="genre">Genre</label>
                                    <select name="genre" id="genre" class="form-control form-control-sm">
                                        <option value=""></option>
                                        @foreach($genres as $g)
                                            <option
                                                    value="{{$g->gen_id}}" {{request("genre")==$g->gen_id?"selected":""}}>{{$g->gen_libelle}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="format">Format</label>
                                    <select name="format" id="format" class="form-control form-control-sm">
                                        <option value=""></option>
                                        @foreach($formats as $f)
                                            <option
                                                    value="{{$f->for_id}}" {{request("format")==$f->for_id?"selected":""}}>{{$f->for_libelle}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rubrique">Rubrique</label>
                            <select name="rubrique" id="rubrique" class="form-control form-control-sm">
                                <option value=""></option>
                                @foreach($rubriques as $r)
                                    <option
                                            value="{{$r->rub_id}}" {{request("rubrique")==$r->rub_id?"selected":""}}>{{$r->rub_libelle}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-block"><i
                                            class="mr-1 fas fa-search"></i> Rechercher
                                </button>
                            </div>
                            <div class="col">
                                <a href="{{route("livres.list")}}"
                                   class="btn btn-outline-secondary btn-block"><i class="mr-1 fas fa-eraser"></i>
                                    Effacer</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <h2>Les livres</h2>
            @if ($livres->isEmpty())
                <h6>Aucun résultat ne correspond à votre recherche.</h6>
            @else
                @respo_vente
                <form action="{{action("LivreController@addPhotos")}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    {{method_field("PUT")}}
                    <div class="pb-3">
                        <button type="submit" disabled class="btn btn-primary"><i class="mr-1 fas fa-cloud-upload-alt"></i> Envoyer les photos</button>
                        <button type="reset" class="btn btn-outline-secondary"><i class="mr-1 fas fa-eraser"></i> Réinitialiser</button>
                    </div>
                    @endrespo_vente
                    <div class="row">
                        @foreach ($livres as $livre)
                            @if ($loop->index % 4 == 0)
                    </div>
                    <div class="row">
                        @endif
                        <div class="col-md-3">

                            <div class="card text-center h-100">
                                <a href="{{route('livres.detail', ['id' => $livre->liv_id])}}" class="custom-card h-100"
                                   style="color:black">
                                    <div class="card-body h-100 d-flex flex-column">
                                        <div style="margin-bottom: 8pt">
                                            @include("widgets.livre-img")
                                        </div>
                                        <h6>{{ $livre->liv_titre }}</h6>
                                        <span>{{ money($livre->liv_prixttc) }}</span>
                                        @respo_vente
                                        <div class="mt-auto">
                                            <div class="custom-file text-left mt-2">
                                                <input type="file" class="custom-file-input" name="{{$livre->liv_id}}"
                                                       id="{{$livre->liv_id}}"
                                                       accept=".jpg,.jpeg,.gif,.png">
                                                <label class="custom-file-label"
                                                       id="{{$livre->liv_id}}"><span></span></label>
                                            </div>
                                        </div>
                                        @endrespo_vente
                                        @adherent
                                        <div class="mt-auto">
                                            <div class="mt-2">
                                                @include("widgets.livre-ajout-panier")
                                            </div>
                                        </div>
                                        @endadherent
                                    </div>
                                </a>
                            </div>

                        </div>
                        @endforeach


                    </div>
                    @respo_vente
                    <div class="pt-3">
                        <button type="submit" disabled class="btn btn-primary"><i class="mr-1 fas fa-cloud-upload-alt"></i> Envoyer les photos</button>
                        <button type="reset" class="btn btn-outline-secondary"><i class="mr-1 fas fa-eraser"></i> Réinitialiser</button>
                    </div>
                </form>
                @endrespo_vente
            @endif
        </div>
    </div>
@endsection












