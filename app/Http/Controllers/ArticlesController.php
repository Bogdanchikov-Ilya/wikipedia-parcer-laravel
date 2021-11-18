<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Words;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class ArticlesController extends Controller
{
    //getAll from maim page
    public function index() {
        $articles = Articles::get(['title', 'url', 'size', 'count_words']);
        return $articles;
    }

    //create
    public function create(Request $request) {
        $checkTitle = Articles::where('title', '=', $request->title)->first(); // проверка на существование статьи
        $body = str_replace(array("?","!",",",";",".","-","(",")","—", "'"), "", $request->body);
        preg_replace('/\s+/', ' ', $body);
        preg_replace('/\x{0301}/u', '', $body);
        $count_words = count(explode(' ',$body)); // считаю слова
        $body = mb_strtolower($body);
        if($checkTitle === null) {
            Articles::create([
                'title' => $request->title,
                'body' => $body,
                'url' => $request->url,
                'size' => $request->size,
                'count_words' => $count_words,
            ]); // запушил
        } else {
            return 'Articles whits this name exist';
        }

        $body = mb_strtolower($body);
        $body=explode(' ',$body); // массив слов-атомов для пуша в таблицу слов
        $body = array_unique($body); // удаляю дубликаты в массиве слов новой статьи

        // SELECT * FROM `words` WHERE `text` IN ('в', 'и', 'c')

        $issetWordsInDB = Words::whereNotIn('text', $body)->get(['text'])->pluck('text')->toArray();

        $mergeArrays = array_merge($body, $issetWordsInDB);

        $resultsArray = array_diff($mergeArrays, array_diff_assoc($mergeArrays, array_unique($mergeArrays)));

        Words::insert(['text' => $resultsArray]);

        return $resultsArray;
        dd();
    }

}
