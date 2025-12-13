<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coffee = MenuCategory::create(['name' => 'Signature Coffee', 'sort_order' => 1]);
        $meals = MenuCategory::create(['name' => 'Heavy Meals', 'sort_order' => 2]);
        $snacks = MenuCategory::create(['name' => 'Snacks & Bites', 'sort_order' => 3]);
        $drinks = MenuCategory::create(['name' => 'Refresher Potions', 'sort_order' => 4]);

        MenuItem::create([
            'menu_category_id' => $coffee->id,
            'name' => 'Caramel Macchiato',
            'description' => 'Espresso poured over vanilla-steamed milk, marked with caramel drizzle.',
            'price' => 35000,
            'is_bestseller' => true,
            'image_path' => 'menu/macchiato.jpg', // Placeholder
        ]);

        MenuItem::create([
            'menu_category_id' => $coffee->id,
            'name' => 'Long Black',
            'description' => 'Double shot espresso over hot water. Strong and bold.',
            'price' => 25000,
        ]);

        MenuItem::create([
            'menu_category_id' => $meals->id,
            'name' => 'The Dungeon Burger',
            'description' => '200g juicy beef patty, melted cheddar, caramelized onions, and our secret BBQ sauce.',
            'price' => 65000,
            'discount_price' => 55000,
            'is_bestseller' => true,
            'image_path' => 'menu/burger.jpg',
        ]);

        MenuItem::create([
            'menu_category_id' => $meals->id,
            'name' => 'Dragon Rice Bowl',
            'description' => 'Spicy chicken karaage served over aromatic butter rice with a soft-boiled egg.',
            'price' => 45000,
        ]);

        MenuItem::create([
            'menu_category_id' => $snacks->id,
            'name' => 'Loaded Fries',
            'description' => 'Crispy fries topped with minced beef and cheese sauce.',
            'price' => 35000,
        ]);

        MenuItem::create([
            'menu_category_id' => $snacks->id,
            'name' => 'Onion Rings',
            'description' => 'Golden fried onion rings served with tartar sauce.',
            'price' => 25000,
            'is_available' => false,
        ]);

        MenuItem::create([
            'menu_category_id' => $drinks->id,
            'name' => 'Blue Lagoon',
            'description' => 'A refreshing soda mix with blue curacao and lemon.',
            'price' => 28000,
            'discount_price' => 25000,
        ]);
    }
}
