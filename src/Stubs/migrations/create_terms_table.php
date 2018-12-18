<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('taxonomy_id')->unsigned()->nullable();
            $table->text('slug');
            $table->text('name');
            $table->timestamps();

            $table->foreign('taxonomy_id')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('terms');
    }
}
