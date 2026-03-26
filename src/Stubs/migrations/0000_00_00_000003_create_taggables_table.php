<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $blueprint): void {
            $blueprint->integer('term_id')->unsigned();
            $blueprint->integer('taggable_id')->unsigned();
            $blueprint->string('taggable_type');

            $blueprint->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::drop('taggables');
    }
};
