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
        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['booking_date', 'status'], 'idx_bookings_date_status');
            $table->index('room_id', 'idx_bookings_room_id');
        });

        Schema::table('board_games', function (Blueprint $table) {
            $table->index('title', 'idx_board_games_title');
            $table->index(['min_players', 'max_players'], 'idx_board_games_players');
            $table->index('status', 'idx_board_games_status');
        });

        Schema::table('board_game_category', function (Blueprint $table) {
            $table->index('category_id', 'idx_board_game_category_category_id');
            $table->index('board_game_id', 'idx_board_game_category_board_game_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug', 'idx_categories_slug');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->index('menu_category_id', 'idx_menu_items_category_id');
            $table->index('is_bestseller', 'idx_menu_items_bestseller');
            $table->index('is_available', 'idx_menu_items_available');
        });

        Schema::table('menu_categories', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'idx_menu_categories_active_sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_date_status');
            $table->dropIndex('idx_bookings_room_id');
        });

        Schema::table('board_games', function (Blueprint $table) {
            $table->dropIndex('idx_board_games_title');
            $table->dropIndex('idx_board_games_players');
            $table->dropIndex('idx_board_games_status');
        });

        Schema::table('board_game_category', function (Blueprint $table) {
            $table->dropIndex('idx_board_game_category_category_id');
            $table->dropIndex('idx_board_game_category_board_game_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_slug');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropIndex('idx_menu_items_category_id');
            $table->dropIndex('idx_menu_items_bestseller');
            $table->dropIndex('idx_menu_items_available');
        });

        Schema::table('menu_categories', function (Blueprint $table) {
            $table->dropIndex('idx_menu_categories_active_sort');
        });
    }
};
