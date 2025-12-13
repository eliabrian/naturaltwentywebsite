@extends('layouts.app')

@section('title', 'Menu')

@section('content')

    {{-- 1. HERO HEADER --}}
    <div class="bg-[#6D1919] text-[#F4E7D4] py-16 text-center relative overflow-hidden border-b-4 border-[#BB9045]">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#BB9045 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="relative z-10 max-w-2xl mx-auto px-4">
            <h1 class="text-4xl md:text-6xl font-serif font-bold mb-4 tracking-wide">The Tavern Menu</h1>
            <p class="text-lg text-[#EFDFAB] font-light max-w-lg mx-auto">
                Fuel your adventure with our artisan coffee, hearty meals, and refreshing potions.
            </p>
        </div>
    </div>

    {{-- 2. STICKY CATEGORY NAV --}}
    <div class="sticky top-[100px] z-30 bg-[#F4E7D4] border-b border-[#BB9045]/30 shadow-sm py-4 overflow-x-auto">
        <div class="max-w-6xl mx-auto px-4 flex gap-4 md:justify-center min-w-max">
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="px-5 py-2 rounded-full border border-[#BB9045] text-[#6D1919] font-bold text-sm uppercase tracking-wider hover:bg-[#6D1919] hover:text-[#F4E7D4] transition whitespace-nowrap">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- 3. MENU LIST --}}
    <div class="max-w-6xl mx-auto px-4 py-12 space-y-16">

        @foreach($categories as $category)
            @if($category->items->count() > 0)
                <section id="cat-{{ $category->id }}" class="scroll-mt-32">

                    {{-- Category Title --}}
                    <div class="flex items-center mb-8">
                        <h2 class="text-3xl font-serif font-bold text-[#6D1919]">{{ $category->name }}</h2>
                        <div class="flex-grow h-px bg-[#BB9045] ml-6 opacity-50"></div>
                    </div>

                    {{-- Items Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-x-8 gap-y-10">
                        @foreach($category->items as $item)

                            {{-- MENU ITEM CARD --}}
                            <div class="flex gap-4 group {{ !$item->is_available ? 'opacity-60 grayscale' : '' }}">

                                {{-- Image (Left Side) --}}
                                <div class="flex-shrink-0 w-24 h-24 md:w-32 md:h-32 bg-stone-200 rounded-lg overflow-hidden border border-[#BB9045] relative shadow-md">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        {{-- Placeholder if no image --}}
                                        <div class="w-full h-full flex items-center justify-center bg-[#EFDFAB] text-[#BB9045]">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif

                                    {{-- Sold Out Overlay --}}
                                    @if(!$item->is_available)
                                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                            <span class="text-white text-[10px] font-bold uppercase tracking-widest border border-white px-2 py-1">Sold Out</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Details (Right Side) --}}
                                <div class="flex-grow flex flex-col justify-center">
                                    <div class="flex justify-between items-start mb-1">
                                        <h3 class="text-lg font-bold text-[#6D1919] font-serif leading-tight group-hover:text-[#BB9045] transition">
                                            {{ $item->name }}
                                        </h3>

                                        {{-- Pricing Logic --}}
                                        <div class="text-right">
                                            @if($item->discount_price && $item->discount_price < $item->price)
                                                <div class="flex flex-col items-end">
                                                    <span class="text-xs text-stone-500 line-through decoration-red-500">
                                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-lg font-bold text-red-600">
                                                        Rp {{ number_format($item->discount_price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-lg font-bold text-[#6D1919]">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Badges --}}
                                    @if($item->is_bestseller)
                                        <div class="mb-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#BB9045] text-white uppercase tracking-wider">
                                                <svg class="w-3 h-3 mr-1 text-yellow-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                Bestseller
                                            </span>
                                        </div>
                                    @endif

                                    <p class="text-sm text-stone-600 leading-relaxed line-clamp-2">
                                        {{ $item->description }}
                                    </p>
                                </div>

                            </div>
                            {{-- End Item Card --}}

                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach

    </div>

@endsection
