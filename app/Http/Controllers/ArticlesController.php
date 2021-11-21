<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Words;
use App\Models\Wordsarticles;
use Illuminate\Http\Request;

use function array_merge;

class ArticlesController extends Controller
{
    // get all articles
    public function index() {
        $articles = Articles::get(['title', 'url', 'size', 'count_words']);
        return response($articles, 200);
    }

    //create article
    public function create(Request $request) {
        $checkTitle = Articles::where('title', '=', $request->title)->first();
        $body = str_replace(array("?","!",",",";",".","-","(",")","—", "'"), "", $request->body);
        $body = preg_replace('/\s+/', ' ', $body);
        $body = preg_replace('/\x{0301}/u', '', $body);
        $count_words = count(explode(' ',$body)); // считаю слова
        $body = mb_strtolower($body);
        if($checkTitle === null) { // проверка на существование статьи
            Articles::create([
                'title' => $request->title,
                'body' => $body,
                'url' => $request->url,
                'size' => $request->size,
                'count_words' => $count_words,
            ]); // запушил
        } else {
            return response(json_encode('Articles whits this name exist'), 400);
        }

        $body = mb_strtolower($body);
        $body=explode(' ',$body); // массив слов-атомов для пуша в таблицу слов
        $body = array_unique($body); // удаляю дубликаты в массиве слов новой статьи

        $issetWordsInDB = Words::whereIn('text', $body)->get(['text'])->pluck('text')->toArray();
        $mergeArrays = array_merge($body, $issetWordsInDB);
        $resultsArray = array_diff($mergeArrays, array_diff_assoc($mergeArrays, array_unique($mergeArrays)));

        // совмещаю старые и новые слова и удаляю повторы
        $wordsArrayForInsert = [];
        foreach ($resultsArray as $value) {
            $wordsArrayForInsert[] = ['text' => $value];
        }

        if(count($wordsArrayForInsert) > 0){
            Words::insert($wordsArrayForInsert); // если есть новые слова пушу
        }
        $relation  = [];
        // заполняю  массив $relation для вставки в результирующую таблицу
        // беру каждую статью и пересчитываю в ней колечество повторений с уже добавленными словами
        foreach (Words::whereIn('text', $body)->get(['id', 'text'])->toArray() as $word){
            foreach (Articles::get(['id', 'body'])->toArray() as $article){
                $counter = substr_count(mb_strtolower(' '.$article["body"].' '), ' '.$word["text"].' ');
                if($counter !== 0){
                    $relation[] = ['word_id' => $word["id"], 'article_id' => $article["id"], 'counter' => $counter];
                }
            }
        }

        //беру все старые записи
        $wordsarticles = Wordsarticles::all()->toArray();
        // вставляю все старые и новые записи в результирующую таблицу
        Wordsarticles::insertOrIgnore(array_merge($wordsarticles, $relation));
        return response(json_encode('Запись добавлена'), 200);
    }
}
