<?php

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

function exec_cmd($cmd)
{
    $out = [];
    exec($cmd, $out);
    return implode("\n", $out);
}

function deci($x, $n = 2)
{
    return number_format($x, $n, ",", ".");
}

function money($x)
{
    return deci($x) . " €";
}

function currentYear()
{
    if (date('n') >= 9)
        return date('Y');
    else
        return date('Y') - 1;
}

function currentYearDisp($yr = null)
{
    $yr = (int)($yr ?: currentYear());
    return $yr . "-" . ($yr + 1);
}

function currentSem()
{
    if (date('n') >= 9)
        return 0;
    else
        return 1;
}

function currentSemXML($niv)
{
    return \App\Donnees::getSemestre(currentYear(), $niv, currentSem());
}

function currentSemDisp($niv)
{
    return currentSemXML($niv)["nom"];
}

function prettyPrintXML(SimpleXMLElement $elem)
{
    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($elem->asXML());
    return $dom->saveXML();
}

function lighten(string $str)
{
    return preg_replace('/[^a-z0-9]/', '', Str::slug($str));
}

function isGit()
{
    return file_exists(base_path() . '/.git');
}

function getCurrentCommit()
{
    return exec_cmd("git rev-parse HEAD");
}

function markdown($str)
{
    return new HtmlString(
        app(Parsedown::class)->text($str)
    );
}