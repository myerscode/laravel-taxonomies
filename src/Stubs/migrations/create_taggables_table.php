<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggablesTable extends Migration
{
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->integer('term_id')->unsigned();
            $table->integer('taggable_id')->unsigned();
            $table->string('taggable_type');

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('taggables');
    }
}