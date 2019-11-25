@extends('layouts.app')

@section("title", "Informations")

<?php
function old_($name)
{
    $default = "";

    if (auth()->check())
    {
        $default = auth()->user()->$name;
    }

    return old($name, $default);
}
?>

@section('content')
    <div class="w-100">
        <div class="card mx-auto text-center" style="width: 550px">
            <div class="card-header"><h4
                        class="mb-0">{{isset($edit) ? "Modifier les informations" : "Informations"}}</h4></div>

            <div class="card-body">
                <form method="POST" action="{{ request()->getRequestUri()  }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @if(isset($edit))
                        {{method_field("PATCH")}}
                    @endif

                    @include("widgets.info", ["src" => "user"])

                    @if (isset($edit))
                        <div class="form-group">
                            <input id="password_old" type="password"
                                   class="form-control{{ $errors->has('password_old') ? ' is-invalid' : '' }}"
                                   name="password_old" required
                                   placeholder="Ancien mot de passe *">

                            <div class="feedback mb-3">
                                Par mesure de sécurité, veuillez saisir votre mot de passe actuel afin de pouvoir
                                effectuer des modifications.
                            </div>

                            @include("widgets.field-error", ["field" => "password_old"])
                        </div>
                    @endif

                    <div class="feedback mb-3 mt-0">
                        Les champs marqués d'une astérisque sont obligatoires.
                    </div>

                    <div class="form-group">
                        <input id="nom" type="text"
                               class="form-control{{ $errors->has('nom') ? ' is-invalid' : '' }}"
                               name="nom"
                               placeholder="Nom *"
                               value="{{ old_('nom') }}" required
                               maxlength="20" autofocus>

                        @include("widgets.field-error", ["field" => "nom"])
                    </div>

                    <div class="form-group">
                        <input id="email" type="email"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                               placeholder="Adresse e-mail *"
                               value="{{ old_('email') }}" required autofocus>

                        @include("widgets.field-error", ["field" => "email"])
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-0">
                                <input id="password" type="password" minlength="8"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password"
                                       placeholder="Mot de passe @if(!isset($edit))*@endif">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-0">
                                <input id="password_confirmation" type="password" minlength="8"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password_confirmation"
                                       placeholder="Confirmer le mot de passe @if(!isset($edit))*@endif">
                            </div>
                        </div>
                        <div class="feedback mb-3">
                            Le mot de passe doit comporter au moins 8 caractères.
                        </div>
                    </div>

                    @include("widgets.field-error", ["field" => "password"])

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            {{isset($edit) ? "Envoyer" : "Inscription"}}
                        </button>
                    </div>

                    @if(!isset($edit))
                        <div class="form-group mt-3 mb-0">
                            <small class="text-secondary d-block">
                                En cliquant sur Inscription, vous acceptez de cliquer sur Inscription.
                            </small>
                        </div>
                    @endif
                </form>
            </div>
        </div>

    </div>
@endsection
