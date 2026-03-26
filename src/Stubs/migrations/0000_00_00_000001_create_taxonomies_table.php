<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('slug');
            $table->text('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('taxonomies');
    }
};
