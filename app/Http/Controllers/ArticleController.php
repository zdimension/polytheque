<?php

namespace App\Http\Controllers;

use App\Article;

class ArticleController extends Controller
{
    public function voir(Article $art)
    {
        return view("article-view", ["art" => $art]);
    }

    public function creer()
    {
        return view("article-edit", ["title" => "", "content" => "", "create" => true]);
    }

    public function creerEnvoyer()
    {
        $art = new Article;
        $art->auteur()->associate(auth()->user());

        return $this->envoyer($art);
    }

    public function modifier(Article $art)
    {
        return view("article-edit", ["art" => $art]);
    }

    public function envoyer(Article $art)
    {
        $art->titre = request("titre");
        $art->contenu = request("contenu");
        $art->save();

        return redirect(route("article.view", ["art" => $art]));
    }

    public function supprimer(Article $art)
    {
        $art->delete();

        return redirect(route("root"));
    }
}