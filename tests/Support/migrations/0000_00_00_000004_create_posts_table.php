<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::drop('posts');
    }

    public function up(): void
    {
        Schema::create('posts', function (Blueprint $blueprint): void {
            $blueprint->increments('id');
            $blueprint->string('slug');
            $blueprint->string('title');
            $blueprint->timestamps();
        });
    }
};
