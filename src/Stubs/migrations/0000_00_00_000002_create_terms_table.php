<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::drop('terms');
    }
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $blueprint): void {
            $blueprint->increments('id');
            $blueprint->integer('taxonomy_id')->unsigned()->nullable();
            $blueprint->text('slug');
            $blueprint->text('name');
            $blueprint->timestamps();

            $blueprint->foreign('taxonomy_id')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }
};
