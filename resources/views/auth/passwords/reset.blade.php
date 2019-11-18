@extends('layouts.app')

@section('content')
    <div class="w-100">
        <div class="card mx-auto text-center" style="width: 450px">
            <div class="card-header"><h4 class="mb-0">Réinitialiser le mot de passe</h4></div>

            <div class="card-body">
                <form method="POST" action="{{ route('password.request') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="login" type="text" class="form-control" name="email" placeholder="Adresse e-mail"
                               value="{{ $email ?? old('email') }}" required autofocus>

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
                        <div class="feedback">
                            Le mot de passe doit comporter au moins 8 caractères.
                        </div>
                    </div>

                    @include("widgets.field-error", ["field" => "password"])

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            Valider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
