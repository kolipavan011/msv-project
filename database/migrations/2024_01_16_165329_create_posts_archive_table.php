<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts_tags', function (Blueprint $table) {
            $table->bigInteger('post_id');
            $table->bigInteger('tag_id');
            $table->unique(['post_id', 'tag_id']);
        });

        Schema::create('posts_cat', function (Blueprint $table) {
            $table->bigInteger('post_id');
            $table->bigInteger('cat_id');
            $table->unique(['post_id', 'cat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_tags');
        Schema::dropIfExists('posts_cat');
    }
};
