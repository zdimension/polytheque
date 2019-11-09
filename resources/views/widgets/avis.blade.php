<div class="card @if($loop->last) mb-0 @else mb-3 @endif" id="comm{{$comm->avi_id}}">
    <div class="card-header card-hf-divided">
        <div class="card-hf-column">
            <small><strong>{{$comm->auteur->adh_pseudo}}</strong> a écrit le {{$comm->avi_date}}
            </small>
        </div>
        <div class="card-hf-column">
            <strong>{{$comm->avi_titre}}</strong>
        </div>
        <div class="card-hf-column">
            <input class="rating-star rating-loading" data-size="xs" readonly
                   value="{{$comm->avi_note}}"/>
        </div>
    </div>
    <div class="card-body">
        {{$comm->avi_detail}}
    </div>
    <div class="card-footer card-hf-divided">
        <div class="card-hf-column">
            <form action="{{action("AvisController@vote", ["id" => $comm->avi_id])}}"
                  method="post">
                {{ csrf_field() }}
                <?php
                $adh = auth()->check() && auth()->user()->est(App\User::ADHERENT)
                    && $comm->auteur != auth()->user()->adherent
                    && !in_array($comm->avi_id, session()->get("votes"));
                ?>
                <button {{$adh?"":"disabled"}} type="submit" name="oui" class="btn btn-success">
                    <i class="fa fa-thumbs-up"
                       aria-hidden="true"></i>&nbsp;{{$comm->avi_nbutileoui}}
                </button>
                <button {{$adh?"":"disabled"}} type="submit" name="non" class="btn btn-danger">
                    <i class="fa fa-thumbs-down"
                       aria-hidden="true"></i>&nbsp;{{$comm->avi_nbutilenon}}
                </button>
            </form>
        </div>
        @if (@$affScore)
            <div class="card-hf-column">
                Pertinence :&nbsp;<b>{{deci($comm->score * 100)}} %</b>&nbsp;({{["très mauvais", "mauvais", "moyen", "bon", "très bon", "excellent"][10 * round($comm->score / 2 * 0.99, 1)]}})
            </div>
        @endif
        @auth
            @if (auth()->user()->est(App\User::RESPO_COMM) || (auth()->user()->est(App\User::ADHERENT) && $comm->auteur != auth()->user()->adherent))
                <div class="card-hf-column">
                    <form action="{{action("AvisController@abusif", ["id" => $comm->avi_id])}}"
                          method="post">
                        {{ csrf_field() }}
                        <?php
                        $adh = auth()->check() && auth()->user()->est(App\User::ADHERENT);

                        $deja_sig =
                            $adh && $comm->signalements->contains(function (App\AvisAbusif $abu)
                            {
                                return $abu->adh_id == auth()->user()->adherent->adh_id;
                            });
                        ?>
                        <button {{$adh?"":"disabled"}} type="submit"
                                aria-pressed="{{$deja_sig?"true":"false"}}"
                                class="btn btn-outline-danger {{$deja_sig?"active":""}}">
                            <i class="fa fa-flag"
                               aria-hidden="true"></i>&nbsp;
                            @adherent
                            @if ($deja_sig)
                                Commentaire signalé
                            @else
                                Signaler ce commentaire
                            @endif
                            @else
                                {{$comm->signalements->count()}} {{str_plural("signalement", $comm->signalements->count())}}
                                @endadherent
                        </button>
                    </form>
                </div>
            @endif
            @if (auth()->user()->est(App\User::RESPO_COMM) || (auth()->user()->est(App\User::ADHERENT) && $comm->auteur == auth()->user()->adherent))
                <div class="card-hf-column">
                    <form action="{{route("avis.delete", ["id" => $comm->avi_id, "redirect" => @$delRedirect])}}"
                          method="post">
                        {{ csrf_field() }}
                        {{ method_field("DELETE") }}
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa fa-ban"
                               aria-hidden="true"></i>&nbsp;Supprimer
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>