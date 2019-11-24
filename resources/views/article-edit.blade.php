@extends('layouts.app')

@push("foot")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/ace.js" integrity="sha256-CVkji/u32aj2TeC+D13f7scFSIfphw2pmu4LaKWMSY8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/mode-html.js" integrity="sha256-+LuWQyoA65gA+u1R8aXl/CeNMelII6+kEngEgjYECfI=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/theme-monokai.js" integrity="sha256-Fc4eJOe8KtF8kDLqSR94vUiJ1ohvKDxznSMxI3RavOw=" crossorigin="anonymous"></script>    <script>
        $(document).ready(function() {
            let editor = ace.edit("editor");
            editor.setTheme("ace/theme/monokai");
            editor.session.setMode("ace/mode/html");

            let inp = $('#contenu');
            editor.getSession().on("change", function () {
                inp.val(editor.getSession().getValue());
            });
        });
    </script>
@endpush

@section('content')
    <div class='card bg-light w-100'>
        <div class="card-header">
            <h2 class="mb-0">{{@$create ? "Créer un " : "Modifier l'"}}article</h2>
        </div>
        <div class="card-body">
            <form action="{{@$create ? route("article.createSubmit") : route("article.submit", ["art" => $art])}}" method="post">
                {{ csrf_field() }}
                {{ method_field("PUT") }}

                <div class="form-group">
                    <input id="titre" type="text"
                           class="form-control"
                           name="titre"
                           placeholder="Titre"
                           value="{{ @$art->titre }}" required>
                </div>

                <input type="hidden" id="contenu" name="contenu" value="{{@$art->contenu}}" />

                <div id="editor" class="w-100 mb-3" style="height: 450px; font-size: 14px;">{{@$art->contenu}}</div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        {{@$create ? "Créer" : "Modifier"}}
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection