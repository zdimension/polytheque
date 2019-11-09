@extends('layouts.app')

@section('title', 'Mes adresses')

@push("head")
    <style>
        #map {
            width: 100%;
            height: 300px;
        }
    </style>
@endpush

@push("foot")
    <script>
        $(document).ready(function () {
            var map = L.map('map').setView([48.833, 2.333], 5);

            var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            });

            map.addLayer(osmLayer);

            map.marker = null;

            function loadGeocode(data) {
                if (data["features"].length > 0) {
                    if (map.marker == null) {
                        map.marker = L.marker([0, 0]).addTo(map);
                    }

                    map.marker.bindPopup(data.features[0].properties.label);

                    var loc = data.features[0].geometry.coordinates;
                    var ll = L.latLng(loc[1], loc[0]);
                    map.marker.setLatLng(ll);
                    map.setView(ll, 11);

                    $("#loc_name").val(data.features[0].properties.label);
                    $("#btnSubmit").text('Ajouter "' + data.features[0].properties.label + '"');
                    $("#btnSubmit").prop("disabled", false);

                    $("#adr_cp").val(data.features[0].properties.postcode);
                    $("#adr_ville").val(data.features[0].properties.city);
                    $("#adr_rue").val(data.features[0].properties.name);
                    $("#adr_latitude").val(ll.lat);
                    $("#adr_longitude").val(ll.lng);
                }
            }

            $("#btnSearch").click(function () {
                $.get("https://api-adresse.data.gouv.fr/search/", {
                    "q": $("#search").val(),
                    "limit": 1,
                    "autocomplete": 1,
                    "lat": map.getCenter().lat,
                    "lon": map.getCenter().lng
                }, function (data) {
                    loadGeocode(data);
                });
            });

            $("#search").keypress(function (e) {
                if (e.keyCode == 13) {
                    $("#btnSearch").click();
                }
            });

            @if(!empty(old("adr_longitude")) && !empty(old("adr_latitude")))
            $.get("https://api-adresse.data.gouv.fr/reverse/", <?=json_encode(["lat" => old("adr_latitude"), "lon" => old("adr_longitude")])?>,
                function (data) {
                    loadGeocode(data);
                });
            @endif
        });
    </script>
@endpush

@section("content")
    <div class="row">
        <div class="col-auto">
            <div class='card bg-light' style="min-width: 500px;">
                <div class="card-header">
                    <h4 class="mb-0">Ajouter une adresse</h4>
                </div>
                <div class="card-body">
                    <div class="form-row form-group">
                        <div class="col">
                            <input id="search" type="search" class="form-control" value="{{old("loc_name")}}"/></div>
                        <div class="col-auto">
                            <button id="btnSearch" class="btn btn-primary"><i class="fa fa-search"
                                                                              aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="map"></div>
                    </div>
                    <form action="{{route("compte.adr.add")}}" method="post">
                        {{ method_field("PUT") }}
                        {{ csrf_field() }}
                        <input type="hidden" id="loc_name" name="loc_name"/>
                        <input type="hidden" id="adr_rue" name="adr_rue"/>
                        <input type="hidden" id="adr_cp" name="adr_cp"/>
                        <input type="hidden" id="adr_ville" name="adr_ville"/>
                        <input type="hidden" id="adr_latitude" name="adr_latitude"/>
                        <input type="hidden" id="adr_longitude" name="adr_longitude"/>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group{{ $errors->has('adr_nom') ? ' has-error' : '' }}">
                                    <label for="adr_nom">Dénomination de l'adresse</label>
                                    <input id="adr_nom" type="text"
                                           class="form-control{{ $errors->has('adr_nom') ? ' is-invalid' : '' }}"
                                           name="adr_nom"
                                           value="{{ old('adr_nom') }}" required autofocus maxlength="50">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group{{ $errors->has('adr_type') ? ' has-error' : '' }}">
                                    <label for="adr_type">Type</label>
                                    <select name="adr_type" id="adr_type"
                                            class="form-control{{ $errors->has('adr_type') ? ' is-invalid' : '' }}">
                                        <option value="" selected disabled></option>
                                        <?php
                                        $typ = [
                                            "Livraison",
                                            "Facturation"
                                        ]
                                        ?>
                                        @foreach($typ as $c)
                                            <option
                                                    value="{{$c}}" {{old("adr_type")==$c?"selected":""}}>{{$c}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>

                        @include("widgets.field-error", ["field" => "adr_nom"])

                        @include("widgets.field-error", ["field" => "adr_type"])

                        <div class="form-group{{ $errors->has('adr_complementrue') ? ' has-error' : '' }}">
                            <label for="adr_complementrue">Complément de rue (facultatif)</label>
                            <input id="adr_complementrue" type="text"
                                   class="form-control{{ $errors->has('adr_complementrue') ? ' is-invalid' : '' }}"
                                   name="adr_complementrue"
                                   value="{{ old('adr_complementrue') }}" autofocus maxlength="200">
                        </div>
                        @include("widgets.field-error", ["field" => "adr_complementrue"])

                        <button id="btnSubmit" disabled type="submit" class="btn btn-primary btn-block"><i
                                    class="mr-1 fas fa-plus"></i> Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            @include("widgets.info", ["src" => "adr"])
            <h2 class="mb-3">Mes adresses</h2>
            @if($adresses->isEmpty())
                <h5 class="mt-3">Vous n'avez enregistré aucune adresse.</h5>
            @else
                <ul class="list-group">
                    @foreach($adresses as $a)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-auto d-flex pr-0">
                                    <form action="{{route("compte.adr.delete", ["id" => $a->adr_id])}}" method="post"
                                          class="my-auto">
                                        {{ method_field("DELETE") }}
                                        {{ csrf_field() }}
                                        <button id="btnSubmit" type="submit" class="btn btn-danger"><i
                                                    class="fa fa-times"
                                                    aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col">
                                    <h6>{{$a->adr_nom}} ({{$a->adr_type}})</h6>
                                    {{$a->adr_complementrue}}<br/>
                                    {{$a->adr_rue}}<br/>
                                    {{$a->adr_cp}} {{$a->adr_ville}}<br/>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection












