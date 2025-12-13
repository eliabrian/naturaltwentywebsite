<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;

class MenuController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['items' => function ($query) {
                $query->orderByDesc('is_bestseller')
                    ->orderBy('name');
            }])
            ->get();

        return view('menu.index', compact('categories'));
    }
}
