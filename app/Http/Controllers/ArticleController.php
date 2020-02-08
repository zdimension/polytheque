<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleVote;

class ArticleController extends Controller
{
    public function voir(Article $art)
    {
        return view("article-view", ["art" => $art, "vote" => $this->voteExistant($art)]);
    }

    private function voteExistant(Article $art)
    {
        if (!session()->has("votes"))
            session()->put("votes", []);

        $check = $art->votes->filter(function (ArticleVote $v)
        {
            return in_array($v->id, session("votes"));
        })->first();

        if (!$check && auth()->check())
        {
            $check = $art->votes->where("utilisateur_id", auth()->user()->id)->first();
        }

        return $check;
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
        $art->script = request("script");
        $art->alias = request("alias");
        $art->invisible = request("invisible") || 0;
        $art->sidebar = request("sidebar") || 0;
        $art->save();

        return redirect(route("article.view", ["art" => $art]));
    }

    public function supprimer(Article $art)
    {
        $art->delete();

        return redirect(route("root"));
    }

    public function apercu()
    {
        if (!request()->has("text")) abort(404);

        return markdown(request("text"));
    }

    public function articleAlias($alias)
    {
        return $this->voir(Article::where("alias", $alias)->firstOrFail());
    }

    public function vote(Article $art)
    {
        $data = $this->validate(request(), [
            'vote' => 'required|integer|min:0|max:1',
        ]);

        $check = $this->voteExistant($art);

        if ($check)
        {
            $check->delete();
        }

        if (!$check || $check->positif != $data["vote"])
        {
            $vote = new ArticleVote;

            $vote->article()->associate($art);
            $vote->utilisateur()->associate(auth()->user());
            $vote->positif = (int)$data["vote"];
            $vote->save();

            session()->push("votes", $vote->id);
        }

        return redirect(route("article.view", ["art" => $art]));
    }
}