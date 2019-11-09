@extends('layouts.app')

@section('title', 'Mes points relais')

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

                    @foreach($relais as $r)
            var marker = L.marker(<?=json_encode([$r->rel_latitude, $r->rel_longitude])?>)
                    .addTo(map)
                    .bindPopup(<?=json_encode(
                        "<h5>" . $r->rel_nom . "</h5>" .
                        $r->rel_rue . "<br/>" .
                        $r->rel_cp . " " . $r->rel_ville . '<br/>' .
                        $r->pays->pay_nom
                    )?>);
            marker.rel = <?=json_encode(["id" => $r->rel_id, "nom" => $r->rel_nom])?>;

            marker.on("click", function (ev) {
                $("#relID").val(this.rel.id);
                $("#btnSubmit").text('Ajouter "' + this.rel.nom + '"');
                $("#btnSubmit").prop("disabled", false);
            });

            @endforeach

            function loadGPS() {
                navigator.geolocation.getCurrentPosition((loc) => {
                    map.setView([loc.coords.latitude, loc.coords.longitude]);
                });
            }

            $("#btnSearch").click(function () {
                $.get("https://api-adresse.data.gouv.fr/search/", {
                    "q": $("#search").val(),
                    "limit": 1,
                    "autocomplete": 1,
                    "lat": map.getCenter().lat,
                    "lon": map.getCenter().lng
                }, function (data) {
                    if (data["features"].length > 0) {
                        var loc = data["features"][0]["geometry"]["coordinates"];
                        map.setView(L.latLng(loc[1], loc[0]), 11);
                    }
                });
            });

            $("#search").keypress(function (e) {
                if (e.keyCode == 13) {
                    $("#btnSearch").click();
                }
            });

            $("#btnGPS").click(function () {
                loadGPS();
            });

            loadGPS();
        });
    </script>
@endpush

@section("content")
    <div class="row">
        <div class="col-auto">
            <div class='card bg-light' style="min-width: 500px;">
                <div class="card-header">
                    <h4 class="mb-0">Ajouter un point relais</h4>
                </div>
                <div class="card-body">
                    <div class="form-row form-group">
                        <div class="col">
                            <input id="search" type="search" class="form-control"/></div>
                        <div class="col-auto">
                            <button id="btnSearch" class="btn btn-primary"><i class="fa fa-search"
                                                                              aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="col-auto">
                            <button id="btnGPS" class="btn btn-primary"><i class="fa fa-map-marker-alt"
                                                                           aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="map"></div>
                    </div>
                    <form action="{{route("compte.relais.add")}}" method="post">
                        {{ method_field("PUT") }}
                        {{ csrf_field() }}
                        <input type="hidden" id="relID" name="id"/>
                        <button id="btnSubmit" disabled type="submit" class="btn btn-primary btn-block"><i
                                    class="mr-1 fas fa-plus"></i> Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            @include("widgets.info", ["src" => "rel"])
            <h2 class="mb-3">Mes points relais</h2>
            @if($relais_adh->isEmpty())
                <h5 class="mt-3">Vous n'avez enregistré aucun point relais.</h5>
            @else
                <ul class="list-group">
                    @foreach($relais_adh as $r)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-auto d-flex pr-0">
                                    <form action="{{route("compte.relais.delete", ["id" => $r->rel_id])}}" method="post"
                                          class="my-auto">
                                        {{ method_field("DELETE") }}
                                        {{ csrf_field() }}
                                        <button id="btnSubmit" type="submit" class="btn btn-danger"><i
                                                    class="fa fa-times" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                                <div class="col">
                                    <h6>{{$r->rel_nom}}</h6>
                                    {{$r->rel_rue}}<br/>
                                    {{$r->rel_cp}} {{$r->rel_ville}}<br/>
                                    {{$r->pays->pay_nom}}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection












