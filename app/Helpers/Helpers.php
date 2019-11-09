<?php

function deci($x, $n=2)
{
    return number_format($x, $n, ",", ".");
}

function money($x)
{
    return deci($x) . " €";
}