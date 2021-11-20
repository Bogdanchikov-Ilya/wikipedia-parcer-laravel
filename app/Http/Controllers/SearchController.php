<?php

namespace App\Http\Controllers;

// use App\Models\Articles;
use App\Models\Words;

use Illuminate\Http\Request;
use App\Models\Wordsarticles;


class SearchController extends Controller
{
    public function index(Request $request) {
        if(!$request->text){
            http_response_code(500);
            echo json_encode('Пустая строка');
            die();
        }
        $wordid = Words::where('text', mb_strtolower($request->text))->get('id')->first()->toArray();
        if(!$wordid){
            http_response_code(404);
            echo json_encode('Ничего не нашлось');
            die();
        }
        return Wordsarticles::where('wordsid', '=', $wordid["id"])->join('articles', 'articles.id', '=', 'wordsarticles.articlesid')->select('articles.title', 'counter')->get();
    }
}
