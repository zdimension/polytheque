<?php

namespace App\Http\Controllers;

use App\Article;

class ArticleController extends Controller
{
    public function voir(Article $art)
    {
        return view("article-view", ["art" => $art]);
    }
}