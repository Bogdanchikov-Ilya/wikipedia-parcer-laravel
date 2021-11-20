<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wordsarticles extends Model
{
    protected $fillable = ['wordsid', 'articlesid', 'counter'];
    public $timestamps = false;
}
