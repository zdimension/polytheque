@extends('layouts.app')

@section("title", "Vérifier votre adresse e-mail")

@section('content')
    <div class="w-100">
        <div class="card mx-auto text-center" style="width: 450px">
            <div class="card-header"><h4 class="mb-0">Vérifier votre adresse e-mail</h4></div>

            <div class="card-body">
                @if (session('resent'))
                    @include("widgets.alert", ["message" => "Un nouveau lien de vérification a été envoyé à votre adresse e-mail."])
                @endif

                <p>
                    Avant de continuer, veuillez vérifier vos e-mails.
                </p>
                <p class="mb-0">
                    Si vous n'avez pas reçu d'e-mail, <a href="{{ route('verification.resend') }}">cliquez ici pour en
                        renvoyer un</a>.
                </p>
            </div>
        </div>
    </div>
@endsection
