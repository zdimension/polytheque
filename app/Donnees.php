<?php


namespace App;


class Donnees
{
    private static $xml = null;

    public static function init()
    {
        if (self::$xml !== null) return;

        self::$xml = new \SimpleXMLElement(file_get_contents(resource_path() . "/data/annees.xml"));
    }

    public static function getAnnee($n = null)
    {
        $n = $n ?: currentYear();
        return self::$xml->xpath("annee[@a=$n]")[0];
    }

    private static function getNom()
    {
        return function ($x)
        {
            return $x["nom"];
        };
    }

    private static function getNoms(array $a)
    {
        return array_map(self::getNom(), $a);
    }

    public static function getNiveaux(string $n = null)
    {
        return self::getNoms(self::getAnnee($n)->xpath("niveau"));
    }

    public static function getSemestre(string $annee, int $niveau, int $semestre)
    {
        return self::getAnnee($annee)->niveau[$niveau]->semestre[$semestre];
    }

    public static function getColorations($sem)
    {
        return self::getNoms($sem->xpath(".//coloration"));
    }

    public static function getCursusInfo(Cursus $c)
    {
        $annee = self::getAnnee($c->annee);
        $niveau = $annee->niveau[$c->niveau];
        $semestre = $niveau->semestre[$c->semestre];

        $cols = self::getColorations($semestre);

        return [
            "annee" => $c->annee,
            "annee_disp" => currentYearDisp($c->annee),
            "niveau" => $niveau["nom"],
            "semestre" => $semestre["nom"],
            "coloration" => $c->coloration === null ? null : $cols[$c->coloration],
            "contenu" => $semestre->children()
        ];
    }

    public static function getCurrentYearNiv(Utilisateur $usr)
    {
        $cur = $usr->cursus->where("annee", currentYear());

        if (!$cur) return null;

        return $cur[0]->niveau;
    }
}

Donnees::init();