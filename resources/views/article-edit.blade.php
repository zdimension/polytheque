@extends('layouts.app')

@section("title", "Modification d'article")

@push("foot")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/ace.js"
            integrity="sha256-CVkji/u32aj2TeC+D13f7scFSIfphw2pmu4LaKWMSY8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/mode-html.js"
            integrity="sha256-+LuWQyoA65gA+u1R8aXl/CeNMelII6+kEngEgjYECfI=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/mode-markdown.js"
            integrity="sha256-y7CQ+vmcCTzRcZRodqknIaPkRg2nFIS91PMCbjofpAI=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.7/mode-javascript.js"
            integrity="sha256-9nVHCZW1SuyhaVgqmPk1XutGn+g/ASVBiVAheioldo4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/theme-monokai.js"
            integrity="sha256-Fc4eJOe8KtF8kDLqSR94vUiJ1ohvKDxznSMxI3RavOw=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            let editor = ace.edit("editor");
            editor.setTheme("ace/theme/monokai");
            editor.session.setMode("ace/mode/markdown");

            let inp = $('#contenu');
            let inp_js = $('#script');
            editor.getSession().on("change", refreshField);

            let editor_js = ace.edit("editor-script");
            editor_js.setTheme("ace/theme/monokai");
            editor_js.session.setMode("ace/mode/javascript");
            editor_js.getSession().on("change", refreshFields);

            function refreshFields() {
                inp.val(editor.getSession().getValue());
                inp_js.val(editor_js.getSession().getValue());
            }

            editor.getSession().setValue({!! @json_encode($art->contenu) ?? "" !!} || ""
        )
            ;

            editor_js.getSession().setValue({!! @json_encode($art->script) ?? "" !!} || ""
        )
            ;

            refreshFields();

            let last = null;
            let preview = $("#preview");

            setInterval(function () {
                if (last === inp.val()) return;

                $.post("{{route("article.preview")}}", {
                    "text": inp.val(),
                    "_token": "{{csrf_token()}}"
                }, function (data) {
                    preview.html(data);
                });

                last = inp.val();
            }, 200);

            window.onbeforeunload = function (e) {
                e = e || window.event;
                if (e) {
                    e.returnValue = "T'es sûr fréro?";
                }
                return "T'es sûr fréro?";
            };
        });
    </script>
@endpush

@section('content')
    <div class='card bg-light w-100'>
        <div class="card-header">
            <h2 class="mb-0">{{@$create ? "Créer un " : "Modifier l'"}}article</h2>
        </div>
        <div class="card-body">
            <form action="{{@$create ? route("article.createSubmit") : route("article.submit", ["art" => $art])}}"
                  method="post">
                {{ csrf_field() }}
                {{ method_field("PUT") }}

                <div class="form-group row">
                    <label for="titre" class="col-sm-2 col-form-label">Titre</label>
                    <div class="col-sm-10">
                        <input id="titre" type="text"
                               class="form-control"
                               name="titre"
                               placeholder="Titre"
                               value="{{ @$art->titre }}" required>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label for="alias" class="col-sm-2 col-form-label">Alias</label>
                    <div class="col-sm-10">
                        <input id="alias" type="text"
                               class="form-control"
                               name="alias"
                               placeholder="Alias"
                               value="{{ @$art->alias }}" pattern="[A-Za-z0-9\-\/]+">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label for="invisible" class="col-sm-2 col-form-label">Invisible</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"
                                   name="invisible" id="invisible" @if(@$art->invisible) checked @endif>
                            <label class="custom-control-label" for="invisible"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sidebar" class="col-sm-2 col-form-label">Sidebar</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"
                                   name="sidebar" id="sidebar" @if(@$art->sidebar) checked @endif>
                            <label class="custom-control-label" for="sidebar"></label>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="contenu" name="contenu"/>
                <input type="hidden" id="script" name="script"/>

                <div class="row mb-3">
                    <div class="col w-50 pr-1">
                        <div id="editor" class="w-100" style="height: 100%; font-size: 14px;"></div>
                    </div>
                    <div class="col w-50 pl-1">
                        <div class="border" style="height: 450px; overflow: scroll;">
                            <div id="preview" class="markdown"></div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div id="editor-script" class="w-100" style="height: 100%; font-size: 14px;"></div>
                </div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        {{@$create ? "Créer" : "Modifier"}}
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection