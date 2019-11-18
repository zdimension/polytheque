@extends('layouts.app')

@section('content')
    <div class="w-100">
        <div class="card mx-auto text-center" style="width: 450px">
            <div class="card-header"><h4 class="mb-0">Connexion</h4></div>

            <div class="card-body">
                <form method="POST" action="{{ request()->getRequestUri()  }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="login" type="text" class="form-control" name="email" placeholder="Adresse e-mail"
                               value="{{ old('email') }}" required autofocus>

                        @include("widgets.field-error", ["field" => "email"])
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control" name="password" required
                               placeholder="Mot de passe">

                        @include("widgets.field-error", ["field" => "password"])
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"
                                   name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Connexion
                        </button>
                    </div>

                    <div class="form-group mb-0">
                        <a class="btn btn-link pb-0" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
