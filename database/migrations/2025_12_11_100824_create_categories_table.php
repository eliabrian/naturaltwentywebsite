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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->default('#6D1919');
            $table->timestamps();
        });

        Schema::create('board_games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('min_players')->default(2);
            $table->integer('max_players')->default(4);
            $table->integer('playtime_min')->nullable();
            $table->integer('playtime_max')->nullable();
            $table->tinyInteger('complexity')->default(1);
            $table->string('shelf_location')->nullable();
            $table->string('status')->default('available');
            $table->timestamps();
        });

        Schema::create('board_game_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_game_category');
        Schema::dropIfExists('board_games');
        Schema::dropIfExists('categories');
    }
};
