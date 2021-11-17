<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    //getAll from maim page
    public function index() {
        $articles = Articles::get(['title', 'url', 'size', 'count_words']);
        return $articles;
    }

    //create
    public function create(Request $request) {
        return Articles::create([
            'title' => $request->title,
            'body' => $request->body,
            'url' => $request->url,
            'size' => $request->size,
            'count_words' => $request->count_words,
        ]);
    }

}
