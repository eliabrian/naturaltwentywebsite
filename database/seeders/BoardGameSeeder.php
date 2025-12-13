<?php

namespace Database\Seeders;

use App\Models\BoardGame;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BoardGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Strategy', 'slug' => 'strategy', 'color' => '#6D1919'],
            ['name' => 'Party Game', 'slug' => 'party', 'color' => '#BB9045'],
            ['name' => 'Family', 'slug' => 'family', 'color' => '#4CAF50'],
            ['name' => '2-Player', 'slug' => '2-player', 'color' => '#2196F3'],
            ['name' => 'Cooperative', 'slug' => 'coop', 'color' => '#9C27B0'],
            ['name' => 'Card Game', 'slug' => 'card', 'color' => '#FF9800'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $games = [
            [
                'title' => 'Catan',
                'description' => 'Collect resources and build settlements on the island of Catan. Trade with players to grow your empire.',
                'cover_image' => 'boardgames/catan.jpg', // Placeholder path
                'min_players' => 3,
                'max_players' => 4,
                'playtime_min' => 60,
                'playtime_max' => 120,
                'complexity' => 3, // 1-5
                'shelf_location' => 'A-1',
                'status' => 'available',
                'categories' => ['strategy', 'family'],
            ],
            [
                'title' => 'Codenames',
                'description' => 'A social word game with a simple premise and challenging gameplay. Two rival spymasters know the secret identities of 25 agents.',
                'cover_image' => 'boardgames/codenames.jpg',
                'min_players' => 4,
                'max_players' => 8,
                'playtime_min' => 15,
                'playtime_max' => 15,
                'complexity' => 1,
                'shelf_location' => 'B-3',
                'status' => 'available',
                'categories' => ['party', 'card', 'family'],
            ],
            [
                'title' => 'Splendor',
                'description' => 'A game of chip-collecting and card development. Players are merchants of the Renaissance trying to buy gem mines.',
                'cover_image' => 'boardgames/splendor.jpg',
                'min_players' => 2,
                'max_players' => 4,
                'playtime_min' => 30,
                'playtime_max' => 30,
                'complexity' => 2,
                'shelf_location' => 'A-2',
                'status' => 'available',
                'categories' => ['strategy', 'family', '2-player'],
            ],
            [
                'title' => 'Pandemic',
                'description' => 'Your team of experts must prevent the world from succumbing to a viral pandemic.',
                'cover_image' => 'boardgames/pandemic.jpg',
                'min_players' => 2,
                'max_players' => 4,
                'playtime_min' => 45,
                'playtime_max' => 60,
                'complexity' => 3,
                'shelf_location' => 'C-1',
                'status' => 'maintenance', // Example of unavailable game
                'categories' => ['strategy', 'coop'],
            ],
            [
                'title' => 'Exploding Kittens',
                'description' => 'A kitty-powered version of Russian Roulette. Players draw cards until someone draws an Exploding Kitten.',
                'cover_image' => 'boardgames/kittens.jpg',
                'min_players' => 2,
                'max_players' => 5,
                'playtime_min' => 15,
                'playtime_max' => 15,
                'complexity' => 1,
                'shelf_location' => 'B-4',
                'status' => 'available',
                'categories' => ['party', 'card'],
            ],
        ];

        foreach ($games as $data) {
            // Extract categories to attach later
            $gameCats = $data['categories'];
            unset($data['categories']);

            // Create the game
            $game = BoardGame::updateOrCreate(['title' => $data['title']], $data);

            // Find Category IDs and Attach
            $catIds = Category::whereIn('slug', $gameCats)->pluck('id');
            $game->categories()->sync($catIds);
        }
    }
}
