@extends('layouts.app')

@section('title', 'Cursus')

@push("foot")
    <script>
        $(document).ready(function () {

        });
    </script>
@endpush

@section("content")
    <div class="row">
        <div class="col-auto">
            <div class='card bg-light' style="min-width: 400px;">
                <div class="card-header">
                    <h4 class="mb-0">Ajouter un cursus</h4>
                </div>
                <div class="card-body">
                    <form action="{{route("cursus.add")}}" method="post">
                        {{ method_field("PUT") }}
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="annee">Année scolaire</label>
                                    <input name="annee" type="text" class="form-control form-control-sm"
                                           disabled value="{{currentYearDisp()}}"/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="niveau">Niveau</label>
                                    <?php
                                    $excl = \App\Donnees::getCurrentYearNiv(auth()->user());
                                    $niv = \App\Donnees::getNiveaux();

                                    if ($excl !== null)
                                    {
                                        session()->put("cursus_add_niveau", $excl);
                                    }
                                    ?>
                                    <select name="niveau" id="niveau"
                                            class="form-control form-control-sm{{ $errors->has('niveau') ? ' is-invalid' : '' }}"
                                            onchange="this.form.submit()"
                                            @if ($excl !== null) disabled @endif>
                                        <option value="" selected disabled></option>

                                        @if ($excl !== null)
                                            <option value="{{$excl}}" selected>{{$niv[$excl]}}</option>
                                        @else
                                            @foreach($niv as $i => $n)
                                                <option
                                                        value="{{$i}}" {{session()->get("cursus_add_niveau")!== null && session()->get("cursus_add_niveau")== $i?"selected":""}}>{{$n}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        @include("widgets.field-error", ["field" => "niveau", "classes" => "mt-0"])

                        @if (session()->get("cursus_add_niveau") !== null)
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="semestre">Semestre</label>
                                        <select name="semestre" disabled class="form-control form-control-sm">
                                            <option selected
                                                    disabled>{{currentSemDisp(session()->get("cursus_add_niveau"))}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    @if ($excl === null)
                                        <div class="form-group">
                                            <label for="coloration">Coloration</label>
                                            <?php
                                            $col =
                                                \App\Donnees::getColorations(currentSemXML(session()->get("cursus_add_niveau")));
                                            ?>
                                            <select name="coloration" id="coloration"
                                                    <?= $col ? "" : "disabled"?>
                                                    class="form-control form-control-sm{{ $errors->has('coloration') ? ' is-invalid' : '' }}">
                                                <option value="" selected disabled></option>

                                                @forelse($col as $i => $c)
                                                    <option
                                                            value="{{$i}}" {{old("coloration")==$c?"selected":""}}>{{$c}}</option>
                                                @empty
                                                    <option value="" selected disabled>— Non concerné —</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @include("widgets.field-error", ["field" => "coloration"])

                        <div class="form-row">
                            <div class="col">
                                <button name="ajouter" type="submit" class="btn btn-primary btn-block"
                                        @if ($excl !== null) disabled>Semestre déjà ajouté@else>Ajouter @endif
                                </button>
                            </div>
                            <div class="col">
                                <a href="{{route("cursus.clearForm")}}" class="btn btn-outline-secondary btn-block">Effacer</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <h2 class="mb-3">Mes cursus</h2>
            @if($cursus->isEmpty())
                <h5 class="mt-3">Vous n'avez renseigné aucun cursus.</h5>
            @else
                <ul class="list-group">
                    @foreach($cursus as $annee => $sems)
                        <?php $sems = $sems->map(function ($s)
                        {
                            return \App\Donnees::getCursusInfo($s);
                        }); ?>
                        <div class="card @if($loop->last) mb-0 @else mb-3 @endif">
                            <div class="card-header card-hf-divided">
                                <div class="card-hf-column">
                                    <small>{{$sems[0]["annee_disp"]}}</small>
                                </div>
                                <div class="card-hf-column">
                                    <strong>{{$sems[0]["niveau"]}}</strong>
                                </div>
                            </div>
                            <div class="card-body row">
                                @foreach($sems as $c)
                                    <div class="col py-0 @if(!$loop->last) pr-0 @endif">
                                        <div class="card">
                                            <div class="card-header card-hf-divided">
                                                <div class="card-hf-column">
                                                    <strong>{{$c["semestre"]}}</strong>
                                                </div>
                                                <div class="card-hf-column">
                                                    @if ($c["coloration"] !== null)
                                                        Coloration :&nbsp;<strong>{{$c["coloration"]}}</strong>
                                                    @else
                                                        Pas de coloration
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                abc<br/>
                                                abc<br/>
                                                abc<br/>
                                                abc<br/>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection












