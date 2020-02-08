<?php

namespace App\Http\Controllers;


use App\Cursus;
use App\Donnees;
use App\Http\Controllers\Auth\RegisterController;
use App\Utilisateur;

class CursusController extends Controller
{
    public function index()
    {
        return view("cursus-list", [
            'cursus' => auth()->user()->cursus->sortBy("semestre")->groupBy("annee")
        ]);
    }

    public function ajouter()
    {
        $data = $this->validate(request(), [
            "niveau" => "required|integer|between:0," . (count(Donnees::getNiveaux()) - 1)
        ], [
            "niveau.required" => "Veuillez choisir un niveau."
        ]);

        $niv = (int)$data["niveau"];

        if (!request()->has("ajouter"))
        {
            session()->put("cursus_add_niveau", $niv);
        }
        else
        {
            $cols = \App\Donnees::getColorations(currentSemXML($niv));

            $col = null;

            if ($cols)
            {
                $data = $this->validate(request(), [
                    "coloration" => "required|integer|between:0," . (count($cols) - 1)
                ], [
                    "coloration.required" => "Vous devez choisir une coloration."
                ]);

                $col = (int)$data["coloration"];
            }

            $cursus = new Cursus;

            $cursus->annee = currentYear();
            $cursus->semestre = currentSem();
            $cursus->niveau = $niv;
            $cursus->coloration = $col;

            auth()->user()->cursus()->save($cursus);

            session()->put("cursus_add_niveau", null);
        }

        return redirect(route("cursus.list"));
    }

    public function effacer()
    {
        session()->put("cursus_add_niveau", null);

        return redirect(route("cursus.list"));
    }
}
