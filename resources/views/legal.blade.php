@extends('layouts.app')

@section("title", "Règles")

@push('head')
    <style>
        .rules li:not(:last-child) {
            margin-bottom: 6px;
        }
    </style>
@endpush

@push('foot')
    <script>
        $(document).ready(function () {
            let counter = 1;

            $(".rules").each(function () {
                $(this).attr("start", counter);
                counter += $(this).find("li").length;
            });
        });
    </script>
@endpush

@section('content')
    <div class='card bg-light w-100'>
        <div class="card-header">
            <h2 class="mb-0">Règles</h2>
        </div>
        <div class="card-body">
            <h4 class="mb-3">Généralités</h4>

            <ol class="rules">
                <li>
                    Ce site est réservé aux élèves de Polytech Annecy-Chambéry, sauf autorisation exceptionnelle de ma
                    part.
                </li>
                <li>
                    L'utilisation des identifiants de connexion d'autrui est formellement interdite, et peut entraîner
                    la suppression définitive de la personne ayant effectué l'intrusion.
                </li>
                <li>
                    Les différentes règles énoncées ici s'appliquent à tous les utilisateurs sans exception, sauf en cas
                    de <a href="{{asset("resources/utile/we5i0xvj11z31.jpg")}}">corruption</a> par voie financière,
                    auquel cas des assouplissements seront possibles.
                </li>
                <li>
                    Tout signalement d'une faille de sécurité sera récompensé par un cookie.
                </li>
                <li>
                    On ne parle pas du <s>Fight Club</s> <a href="{{asset("resources/utile/33858569.png")}}">CC1 de
                        MATH302</a>.
                    <small>Ni du <a
                                href="{{asset("resources/utile/56462404_402806543843630_5623021319076446208_n.png")}}">CC</a>
                        de <a href="{{asset("resources/utile/51892073_1887584891370544_6036024747194580992_n.png")}}">MATH201</a>.</small>
                    <small>Ni du <a
                                href="{{asset("resources/utile/76605211_978624182504313_2953825223377420288_o.jpg")}}">CC
                            de CHIM301</a>.</small>
                </li>
                <li>
                    Les attributes rhô et thêta sont des <a
                            href="{{asset("resources/utile/72706569_2816844148348603_1610175173321818112_n.png")}}">hallucinations</a>.
                </li>
                <li>
                    La règle de De Morgan graphique <a
                            href="{{asset("resources/utile/47572499_2200431936877929_4541149246539169792_n.png")}}">doit
                        être utilisée</a> en toutes circonstances.
                </li>
                <li>
                    Le CC1 de MECA301 était <a
                            href="{{asset("resources/utile/73458986_389566058617550_3396803374283227136_n.png")}}">largement
                        faisable</a> en une heure.
                </li>
                <li>
                    Le nombre de gens présents dans l'amphi est toujours <a
                            href="{{asset("resources/utile/71105397_529672570938360_8217138362784415744_n.jpg")}}">strictement
                        inférieur</a> aux nombre de signatures
                    sur la feuille d'appel.
                </li>
                <li>
                    <a href="https://www.youtube.com/watch?v=l7tURWFLkd0">Aïe aïe aïe.</a>
                </li>
                <li>
                    Moodle <a href="{{asset("resources/utile/4525895.jpg")}}">est</a> <a
                            href="{{asset("resources/utile/52467422_645502415898275_709832878552252416_n.jpg")}}">extrêmement</a>
                    <a href="{{asset("resources/utile/57467954_268903900719714_5163020052408041472_n.png")}}">stable</a>.
                </li>
                <li>
                    Ça ne s'arrête <a
                            href="{{asset("resources/utile/78159900_3006112146084089_3218336440773509120_n.png")}}">jamais</a>.
                </li>
            </ol>

            <h4 class="mb-3">Données personnelles</h4>

            <ol class="rules">
                <li>
                    Le site ne stocke que les informations utiles au compte utilisateur : nom, adresse e-mail, mot de
                    passe <a href="{{asset("resources/utile/0002355.jpeg")}}">hashé</a> en bcrypt et choix de cursus.
                </li>
                <li>
                    Conformément à la loi « informatique et libertés » du 6 janvier 1978, vous bénéficiez d’un droit
                    d’accès et de rectification aux informations qui vous concernent.<br/>
                    Toute demande à sujet pourra
                    être adressée en envoyant DONNÉES au 8 22 22 <small>(86,50€ par envoi + prix d'une Clio II finition
                        cuir de lama)</small>.
                </li>
                <li>
                    Vous pouvez supprimer votre compte à l'aide du bouton situé sur la page <a
                            href="{{route("compte.view")}}">"Mon compte"</a>.
                </li>
            </ol>
        </div>
    </div>
@endsection