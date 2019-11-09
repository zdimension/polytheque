<?php
if (!isset($factor))
    $factor = 1;
?>
@if ($livre->photos->isEmpty())
    <div style="display:inline-block;">
        <div style="padding: 2px;width:{{90*$factor}}px;height:{{130*$factor}}px;border:1px dashed #bbb;
                display: flex;
                align-items: center;">Pas d'image disponible
        </div>
    </div>
@else
    <img src="{{$livre->photos[0]->url()}}" style="width:{{90*$factor}}px;height:{{130*$factor}}px;"><br/>
@endif
