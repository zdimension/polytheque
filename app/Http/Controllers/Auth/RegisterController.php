<?php

namespace App\Http\Controllers\Auth;

use App\Utilisateur;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        return @$_GET["redirect"] ?: route("root");
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return self::getValidator($data);
    }

    public static function validationRules()
    {
        return [
            'email' => 'required|string|email|max:255|unique:' . (new Utilisateur)->getTable(),
            'password' => 'required|string|min:8|confirmed',
            'nom' => 'required|string|max:255'
        ];
    }

    public static function validationMessages()
    {
        return [
            "password.confirmed" => "Les mots de passe ne correspondent pas.",
            "email.unique" => "Un compte avec cette adresse e-mail existe déjà.",
            "email.email" => "L'adresse e-mail n'est pas valide."
        ];
    }

    public static function getValidator(array $data)
    {
        return Validator::make($data, self::validationRules(), self::validationMessages());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Utilisateur
     */

    protected function create(array $data)
    {
        return Utilisateur::create(array_merge($data, [
            "mdp" => password_hash($data["password"], PASSWORD_DEFAULT),
            "privileges" => Utilisateur::NORMAL
        ]));
    }
}
