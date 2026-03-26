<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::drop('taxonomies');
    }

    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $blueprint): void {
            $blueprint->increments('id');
            $blueprint->text('slug');
            $blueprint->text('name');
            $blueprint->timestamps();
        });
    }
};
