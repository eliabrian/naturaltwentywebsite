@extends('layouts.app')

@section('title', 'Games')

@section('content')
    {{-- Hero Section --}}
    <div class="bg-[#6D1919] text-white py-16 px-6 text-center shadow-lg relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-[#BB9045] opacity-10 rounded-full blur-3xl -translate-y-1/2"></div>

        <div class="relative z-10">
            <h1 class="text-4xl md:text-6xl font-bold mb-3 tracking-wide text-[#F4E7D4]">The Grand Archives</h1>
            <p class="text-[#EFDFAB] text-lg font-light tracking-wider uppercase">Explore our collection of {{ $games->total() }} board games</p>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="max-w-6xl mx-auto px-4 -mt-6 relative z-20 mb-10">
        <form action="{{ route('games.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow-xl border-t-4 border-[#BB9045] flex flex-col md:flex-row gap-4 items-end">

            {{-- Search --}}
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-bold text-[#6D1919] uppercase mb-1">Search Title</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Catan..." class="w-full border border-[#BB9045] rounded px-3 py-2 focus:ring-2 focus:ring-[#6D1919] focus:outline-none bg-[#F4E7D4] bg-opacity-20 text-sm">
            </div>

            {{-- Category Filter --}}
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-[#6D1919] uppercase mb-1">Genre</label>
                <select name="category" class="w-full border border-[#BB9045] rounded px-3 py-2 focus:ring-2 focus:ring-[#6D1919] focus:outline-none bg-[#F4E7D4] bg-opacity-20 text-sm">
                    <option value="">All Genres</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Player Count Filter --}}
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-[#6D1919] uppercase mb-1">Player Count</label>
                <select name="players" class="w-full border border-[#BB9045] rounded px-3 py-2 focus:ring-2 focus:ring-[#6D1919] focus:outline-none bg-[#F4E7D4] bg-opacity-20 text-sm">
                    <option value="">Any</option>
                    @foreach([2,3,4,5,6,7,8] as $num)
                        <option value="{{ $num }}" {{ request('players') == $num ? 'selected' : '' }}>{{ $num }} Players</option>
                    @endforeach
                </select>
            </div>

            {{-- Submit --}}
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full bg-[#6D1919] text-[#F4E7D4] font-bold py-2 px-6 rounded hover:bg-[#521313] transition uppercase text-sm tracking-wider">
                    Filter
                </button>
            </div>

            @if(request()->anyFilled(['search', 'category', 'players']))
                <div class="w-full md:w-auto">
                    <a href="{{ route('games.index') }}" class="text-xs text-[#6D1919] underline hover:text-[#BB9045]">Reset</a>
                </div>
            @endif
        </form>
    </div>

    {{-- Game Grid --}}
    <div class="max-w-6xl mx-auto px-4 pb-12 grow w-full">
        @if($games->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($games as $game)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-[#EFDFAB] group h-full flex flex-col">

                        {{-- Image Area --}}
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($game->cover_image)
                                <img src="{{ asset('storage/' . $game->cover_image) }}" alt="{{ $game->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#EFDFAB] text-[#BB9045]">
                                    <span class="text-xs uppercase font-bold">No Cover</span>
                                </div>
                            @endif

                            {{-- Status Badge --}}
                            @if($game->status !== 'available')
                                <div class="absolute top-2 right-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow uppercase tracking-wide">
                                    {{ str_replace('_', ' ', $game->status) }}
                                </div>
                            @endif

                            {{-- Shelf Location (For finding it physically) --}}
                            @if($game->shelf_location)
                                <div class="absolute bottom-0 left-0 bg-[#6D1919] bg-opacity-90 text-[#F4E7D4] text-[10px] font-bold px-2 py-1 rounded-tr">
                                    Shelf: {{ $game->shelf_location }}
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-5 flex flex-col grow">
                            <h3 class="font-serif font-bold text-xl text-[#6D1919] mb-1 leading-tight">{{ $game->title }}</h3>

                            {{-- Genres --}}
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach($game->categories->take(3) as $cat)
                                    <span class="text-[10px] uppercase font-bold text-[#BB9045] border border-[#BB9045] px-1.5 py-0.5 rounded-sm">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                            </div>

                            <p class="text-stone-500 mb-4 line-clamp-2 grow text-xs leading-relaxed">
                                {{ $game->description }}
                            </p>

                            {{-- Stats Row --}}
                            <div class="flex items-center justify-between text-xs font-bold text-stone-600 border-t border-[#F4E7D4] pt-3 mt-auto">
                                {{-- Players --}}
                                <div class="flex items-center" title="Player Count">
                                    <svg class="w-4 h-4 mr-1 text-[#BB9045]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ $game->players }}
                                </div>

                                {{-- Time --}}
                                <div class="flex items-center" title="Playtime">
                                    <svg class="w-4 h-4 mr-1 text-[#BB9045]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $game->duration }}
                                </div>

                                {{-- Complexity (Stars) --}}
                                <div class="flex items-center" title="Complexity: {{ $game->complexity }}/5">
                                    <svg class="w-4 h-4 mr-1 text-[#BB9045]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    {{ $game->complexity }}/5
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $games->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-lg border-2 border-dashed border-[#BB9045] opacity-60">
                <svg class="w-16 h-16 mx-auto text-[#BB9045] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-[#6D1919] font-bold text-lg">No games found</p>
                <p class="text-sm">Try adjusting your filters.</p>
            </div>
        @endif
    </div>
@endsection
