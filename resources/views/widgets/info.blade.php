<?php
if (isset($src))
    $suffix = "_" . $src;
else
    $suffix = "";
?>
@if(session()->get("info" . $suffix))
    @include("widgets.alert", ["close" => true, "message" => session()->get("info" . $suffix)])
@elseif(session()->get("error" . $suffix))
    @include("widgets.alert", ["close" => false, "message" => session()->get("error" . $suffix), "error" => true])
@endif