<div class="alert alert-{{@$error ? "danger" : "success"}} @if(@$close) alert-dismissible fade show @endif {{@$classes}}"
     role="alert" @if (@$id) id="{{$id}}" @endif>
    @if(@$html)
        <div class="alertContent">{!!@$message!!}</div>
    @else
        <span class="alertContent">{{@$message}}</span>
    @endif

    @if(@$close)
        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer" style="margin-top: -1px">
            <li class="fas fa-times"></li>
        </button>
    @endif
</div>
