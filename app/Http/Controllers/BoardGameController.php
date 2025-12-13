<?php

namespace App\Http\Controllers;

use App\Models\BoardGame;
use App\Models\Category;
use Illuminate\Http\Request;

class BoardGameController extends Controller
{
    public function index(Request $request)
    {
        $query = BoardGame::query()->with('categories');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('players')) {
            $p = $request->players;
            $query->where('min_players', '<=', $p)
                ->where('max_players', '>=', $p);
        }

        $games = $query->orderBy('title')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('games.index', compact('games', 'categories'));
    }
}
