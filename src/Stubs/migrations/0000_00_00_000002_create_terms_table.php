<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
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

    public function down(): void
    {
        Schema::drop('terms');
    }
};
