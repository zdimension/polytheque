<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Auth\RegisterController;
use App\Utilisateur;

class UtilisateurController extends Controller
{
    public function voirProfil()
    {
        return view("auth.register", ["edit" => true]);
    }

    public function modifierProfil()
    {
        $data = $this->validate(request(), array_merge(RegisterController::validationRules(), [
            'email' => 'required|string|email|max:80|unique:users,email,' . auth()->user()->id,
            'password_old' => [
                'required',
                function ($_, $val, $fail) {
                    if (!password_verify($val, auth()->user()->mdp)) {
                        $fail("L'ancien mot de passe est incorrect.");
                    }
                }
            ]
        ]), RegisterController::validationMessages());

        if ($data["mdp"] === null)
            unset($data["mdp"]);
        else
            $data["mdp"] = password_hash($data["mdp"], PASSWORD_DEFAULT);

        auth()->user()->update($data);

        session()->flash("info_user", "Les modifications ont bien été enregistrées.");

        return $this->voirProfil();
    }
}
