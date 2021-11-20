<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Words;
use App\Models\Wordsarticles;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use function array_merge;

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
        $body = preg_replace('/\s+/', ' ', $body);
        $body = preg_replace('/\x{0301}/u', '', $body);
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

        $issetWordsInDB = Words::whereIn('text', $body)->get(['text'])->pluck('text')->toArray();

        $mergeArrays = array_merge($body, $issetWordsInDB);

        $resultsArray = array_diff($mergeArrays, array_diff_assoc($mergeArrays, array_unique($mergeArrays)));

        $wordsArrayForInsert = [];
        foreach ($resultsArray as $value) {
            $wordsArrayForInsert[] = ['text' => $value];
        }

        if(count($wordsArrayForInsert) > 0){
            Words::insert($wordsArrayForInsert);
        }

        // ver 2
        // 1. получаю массив idшников НОВЫХ слов (SELECT * FROM `words` WHERE `text` IN ('в','и')
        // 2. получил массив айдишников статей и текстов этих статей (select (id, body) from articles)
        // 3. в цикле беру слово и проверяю сколько раз оно содержится в каждой из статей
        // ->


        // $NewWordsInDB = Words::whereIn('text', $body)->get(['id', 'text'])->toArray();
        // $articlesArray = Articles::get(['id', 'body'])->toArray();

        $relation  = [];
        foreach (Words::whereIn('text', $body)->get(['id', 'text'])->toArray() as $word){
            foreach (Articles::get(['id', 'body'])->toArray() as $article){
                $counter = substr_count(mb_strtolower(' '.$article["body"].' '), ' '.$word["text"].' ');
                if($counter !== 0){
                    $relation[] = ['wordsid' => $word["id"], 'articlesid' => $article["id"], 'counter' => $counter];
                }
            }
        }

        //беру все старые записи
        $wordsarticles = Wordsarticles::all()->toArray();

        Wordsarticles::insertOrIgnore(array_merge($wordsarticles, $relation));

        return array_merge($wordsarticles, $relation);
    }
}
