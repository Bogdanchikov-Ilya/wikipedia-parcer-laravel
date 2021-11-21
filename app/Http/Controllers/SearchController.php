<?php

namespace App\Http\Controllers;


use App\Models\Words;

use Illuminate\Http\Request;
use App\Models\Wordsarticles;


class SearchController extends Controller
{
    public function index(Request $request) {
        if(!$request->text){
            return response($request->text, 500);
        }
        $wordid = Words::where('text', mb_strtolower($request->text))->get('id')->first();
        if(!$wordid){
            return response(json_encode('Not Found'), 404);
        }
        return response(Wordsarticles::where('word_id', '=', $wordid["id"])
            ->join('articles', 'articles.id', '=', 'wordsarticles.article_id')
            ->select('articles.title', 'counter')
            ->get(), 200);
    }
}
