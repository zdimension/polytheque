@section("ogType", "article")

@extends('layouts.app')

@section("title", $art->titre)

@push("head")
    <style>
        #back-top {
            position: fixed;
            bottom: calc(var(--footer-height) + 20px);
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            width: 50px;
            height: 50px;
            display: block;
            text-decoration: none;
            border-radius: 35px;
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
        }

        #back-top.active {
            opacity: 1;
            visibility: visible;
        }

        #back-top i {
            color: #fff;
            margin: 0;
            position: relative;
            left: 25px;
            top: 25px;
            transform: translate(-50%, -60%);
            font-size: 19px;
            transition: all 0.3s ease;
        }

        #back-top:hover {
            background: rgba(0, 0, 0, 0.9);
        }

        #nav-sidebar {
            position: sticky;
            top: 1rem;
            height: 100vh;
        }

        #nav-sidebar ul {
            list-style-type: circle;
            padding-left: 25px;
        }

        #nav-sidebar > ul {
            list-style-type: none;
            padding-left: 0;
        }

        #nav-sidebar a {
            color: black;
        }

        #nav-sidebar li.active > a {
            font-weight: bold;
        }
    </style>
@endpush

@push("foot")
    <script>
        $(document).ready(function() {
            let backtop = $("#back-top");

            $(window).scroll(function() {
                if ($(window).scrollTop() > 300) {
                    backtop.addClass("active");
                }
                else {
                    backtop.removeClass("active");
                }
            });

            $(window).scroll();

            backtop.click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 300);
            });
        });
    </script>

    @if ($art->sidebar)
        <script>
            $(document).ready(function() {
                let c = $("#nav-sidebar");

                let depth = 0;
                let list = $("<ul></ul>");
                let cur_list = list;
                let last = -1;

                $("#article-body :header").each(function(i) {
                    let num = $(this).prop("tagName")[1];

                    if (last !== -1 && num > last)
                    {
                        let n_ul = $("<ul></ul>");
                        cur_list.append(n_ul);
                        cur_list = n_ul;
                        depth++;
                    }
                    else if (num < last && depth > 0)
                    {
                        cur_list = cur_list.parent();
                        depth--;
                    }

                    last = num;

                    let h = $(this);

                    $(this).attr("id", "nav_" + i);

                    cur_list.append(
                        $("<li></li>")
                            .data("heading", $(this))
                            .append(
                                $("<a></a>")
                                    .html($(this).html())
                                    .click(function() {
                                        $("html, body").animate({scrollTop: h.offset().top - 15}, 300);
                                        location.hash = h.attr("id");
                                    })
                                    .attr("href", "#")));
                });

                c.append(list);

                list.width(Math.max.apply(Math, list.children().map(function(){ return $(this).width(); }).get()) + 15);

                $(window).scroll(function() {
                    let found = false;

                    list.find("li").each(function() {
                        if (!found && $(this).data("heading").offset().top - $(window).scrollTop() > 0) {
                            found = true;
                            $(this).addClass("active");
                        }
                        else {
                            $(this).removeClass("active");
                        }
                    });
                });

                $(window).scroll();
            });
        </script>
    @endif
@endpush

@section('content')
    <div class='card bg-light w-100'>
        <div class="card-header card-hf-divided">
            <div class="card-hf-column">
                <h4 class="mb-0 mx-2">{{$art->titre}}</h4>
            </div>
            <div class="card-hf-column">
                par {{$art->auteur->nom}}
            </div>
            <div class="card-hf-column">
                le {{$art->date_creation->format("d/m/Y")}}
            </div>
            @auth
                @if (auth()->user()->est(\App\Utilisateur::AUTEUR))
                    <div class="card-hf-column">
                        <a class="btn btn-primary" href="{{route("article.edit", ["art" => $art])}}"><i
                                    class="fa fa-pen mr-1" aria-hidden="true"></i> Modifier</a>
                    </div>
                    <!--<div class="card-hf-column">
                        <a class="btn btn-danger" style="transform: scale(0.3)" href="{{route("article.delete", ["art" => $art])}}"><i
                                    class="fa fa-trash mr-1" aria-hidden="true"></i> Supprimer</a>
                    </div>-->
                @endif
            @endauth
        </div>
        <div class="card-body markdown row flex-nowrap">
            <div class="col" style="width: 0" id="article-body">
                {!!markdown($art->contenu)!!}
            </div>
            @if ($art->sidebar)
                <div class="col-auto border-left" id="nav-sidebar">
                    <h4>Navigation</h4>
                </div>
            @endif
        </div>
    </div>

    <a href="#" id="back-top"><i class="fa fa-chevron-up"></i></a>
@endsection