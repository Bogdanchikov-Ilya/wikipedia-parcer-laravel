<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsarticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wordsarticles', function (Blueprint $table) {
            $table->unsignedBigInteger('word_id');
            $table->unsignedBigInteger('article_id');
            $table->foreign('word_id')->references('id')->on('words');
            $table->foreign('article_id')->references('id')->on('articles');
            $table->integer('counter');
            $table->primary(['word_id','article_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wordsarticles');
    }
}
