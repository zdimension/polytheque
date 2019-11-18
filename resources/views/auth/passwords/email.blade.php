@extends('layouts.app')

@section('content')
    <div class="w-100">
        <div class="card mx-auto text-center" style="width: 450px">
            <div class="card-header"><h4 class="mb-0">Mot de passe oublié</h4></div>

            <div class="card-body">
                <form method="POST" action="{{ route('password.email') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    @include("widgets.info")

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="login" type="text" class="form-control" name="email" placeholder="Adresse e-mail"
                               value="{{ old('email') }}" required autofocus>

                        @include("widgets.field-error", ["field" => "email"])
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
