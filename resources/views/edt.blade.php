@extends('layouts.app')

@push("head")
    <style>
        #searchLoading {
            width: 1rem;
            height: 1rem;
            vertical-align: middle;
            margin-bottom: 0.2rem;
        }
    </style>
@endpush

@push("foot")
    <script>
        $(document).ready(function () {
            let search = $("#search");
            let searchError = $("#searchError");
            let results = $("#searchResults");

            searchError.hide();
            $("#searchLoading").hide();
            results.hide();

            function showError(html) {
                $("#searchError .alertContent").html(html);
                searchError.show();
            }

            $("#frmSearch").submit(function (e) {
                searchError.hide();
                search.blur().prop("disabled", true);
                $("#btnSearch").prop("disabled", true);
                $("#searchIcon").hide();
                $("#searchLoading").show();
                results.hide();
                results.empty();

                $.get("{{route("edt.search", ["query" => ":q"])}}".replace(":q", encodeURIComponent(search.val())),
                    function (data) {
                        if ("trace" in data && "message" in data) { // laravel foire
                            data["error"] = data["message"];
                        }

                        if (data["error"] !== undefined) {
                            let html = {
                                "tooMany": "La recherche a renvoyé trop de résultats. Veuillez préciser votre requête.",
                                "zero": "La recherche n'a renvoyé aucun résultat. Essayez avec des termes différents."
                            }[data["error"]];

                            if (html === undefined) {
                                html = "Une erreur est survenue lors de la recherche : <pre class='my-1 ml-3'>" + data["error"] +
                                "</pre>Sûrement un coup des chambériens.";
                            }

                            showError(html);
                        }
                        else {
                            for (let res of data) {
                                results.append(
                                    $("<a></a>")
                                        .attr("href", "#")
                                        .addClass("list-group-item")
                                        .addClass("list-group-item-action")
                                        .text(res["path"].replace(/\./g, " ⯈ ") + res["name"])
                                        .click(function(e) {
                                            e.preventDefault();
                                        })
                                );
                            }
                            results.show();

                            $("html, body").animate({ scrollTop: results.offset().top }, 1000);
                        }
                    })
                    .fail(function() {
                        showError("Une erreur inconnue est survenue lors de la recherche.");
                    })
                    .always(function() {
                        search.prop("disabled", false);
                        $("#btnSearch").prop("disabled", false);
                        $("#searchIcon").show();
                        $("#searchLoading").hide();
                    });

                e.preventDefault();
            });
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-6 pr-2">
            <div class='card bg-light'>
                <div class="card-header">
                    <h4 class="mb-0">Emploi du temps</h4>
                </div>
                <div class="card-body">
                    @include("widgets.alert", ["close" => false, "classes" => "alert-info", "html" => true, "message" =>
                    "Saisissez le <strong>nom du groupe</strong> correspondant à votre <strong>emploi du temps</strong>.<br/>
                    <br/>
                    <strong>Exemple :</strong>
                    <ul>
                    <li>Vous êtes en 1<sup>ère</sup> année de PEIP à Chambéry, dans le groupe 112 : <strong>PeiP-112</strong></li>
                    <li>Vous êtes en 2<sup>ème</sup> année de PEIP à Annecy, dans le groupe TP B1 : <strong>PEIP2-B1</strong></li>
                    </ul>

                    Si la recherche ne donne rien, <strong>essayez des termes moins précis</strong>."])
                    <form id="frmSearch">
                        <div class="form-row form-group mb-0">
                            <div class="col">
                                <input id="search" type="search" class="form-control" required minlength="2"
                                       placeholder="Nom du groupe"/>
                            </div>
                            <div class="col-auto">
                                <button id="btnSearch" class="btn btn-primary">
                                    <i class="fa fa-search" aria-hidden="true" id="searchIcon"></i>
                                    <i class="spinner-border text-light" role="status" id="searchLoading">
                                        <span class="sr-only">Chargement...</span>
                                    </i>
                                </button>
                            </div>
                        </div>
                    </form>

                    @include("widgets.alert", ["close" => false, "error" => true, "html" => true, "id" => "searchError", "classes" => "mt-3 mb-0"])

                    <div class="list-group mt-3" id="searchResults">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 pl-2">
            <div class='card bg-light' style="position: sticky; top: 1rem;">
                <div class="card-header">
                    <h4 class="mb-0">Compte Google</h4>
                </div>
                <div class="card-body">
                    dfgf
                </div>
            </div>
        </div>
    </div>
@endsection
