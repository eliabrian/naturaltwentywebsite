@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- 1. HERO SECTION --}}
    <div class="relative bg-[#6D1919] text-white py-24 md:py-32 px-6 text-center overflow-hidden">
        {{-- Background Overlay --}}
        <div class="absolute inset-0 bg-black opacity-40 z-0"></div>
        <div class="absolute inset-0 z-0 opacity-30" style="background-image: url('https://images.unsplash.com/photo-1606167668584-78701c57f13d?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center;"></div>

        <div class="relative z-10 max-w-4xl mx-auto">
            {{-- Animated Subtitle --}}
            <p class="text-[#BB9045] font-bold tracking-[0.3em] uppercase text-sm mb-4 animate-pulse">
                Est. 2025 • Bekasi
            </p>

            <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6 tracking-wide text-[#F4E7D4] drop-shadow-lg">
                Roll the Dice.<br>Make Memories.
            </h1>

            <p class="text-lg md:text-2xl text-stone-200 mb-10 font-light max-w-2xl mx-auto leading-relaxed">
                Step into the first cafe of its kind in the region. Experience our custom-built gaming tables, explore over hundreds of titles, and immerse yourself in a private adventure like never before.
            </p>

            <div class="flex flex-col md:flex-row gap-4 justify-center">
                {{-- CTA: Book --}}
                <a href="{{ route('bookings.create') }}" class="bg-[#BB9045] text-[#6D1919] font-bold py-4 px-10 rounded shadow-lg hover:bg-[#F4E7D4] transition transform hover:-translate-y-1 uppercase tracking-widest text-sm">
                    Book a Room
                </a>
                {{-- CTA: Games --}}
                <a href="{{ route('games.index') }}" class="border-2 border-[#F4E7D4] text-[#F4E7D4] font-bold py-4 px-10 rounded shadow-lg hover:bg-[#F4E7D4] hover:text-[#6D1919] transition transform hover:-translate-y-1 uppercase tracking-widest text-sm">
                    Browse Library
                </a>
            </div>
        </div>
    </div>

    {{-- 2. ABOUT & FEATURES --}}
    <section class="py-20 px-6 max-w-6xl mx-auto bg-[#F4E7D4] text-stone-800">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            {{-- Image Grid --}}
            <div class="relative">
                <div class="absolute -inset-4 bg-[#BB9045] rounded-lg opacity-20 rotate-3"></div>
                <img src="https://images.unsplash.com/photo-1606167668584-78701c57f13d?w=800&q=80" alt="Board Game Cafe" class="relative rounded-lg shadow-2xl border-4 border-white transform hover:scale-105 transition duration-500">
            </div>

            {{-- Text Content --}}
            <div>
                <h2 class="text-4xl font-serif font-bold text-[#6D1919] mb-6">More Than Just a Cafe</h2>
                <div class="w-20 h-1 bg-[#BB9045] mb-6"></div>

                <p class="text-lg text-stone-700 mb-6 leading-relaxed">
                    We believe in the power of analog connection. In a digital world, we provide a space where friends can gather, unplug, and embark on epic tabletop adventures.
                </p>

                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="shrink-0 h-6 w-6 rounded-full bg-[#6D1919] flex items-center justify-center text-[#F4E7D4] mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-[#6D1919]">100+ Board Games</h4>
                            <p class="text-sm text-stone-600">From quick party games to heavy strategy epics, our curated library holds over hundreds of titles.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="shrink-0 h-6 w-6 rounded-full bg-[#6D1919] flex items-center justify-center text-[#F4E7D4] mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-[#6D1919]">D&D Private Room</h4>
                            <p class="text-sm text-stone-600">Featuring a one-of-a-kind, custom-built gaming table designed for total immersion and adjustable lighting to set the perfect mood for your adventure.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="shrink-0 h-6 w-6 rounded-full bg-[#6D1919] flex items-center justify-center text-[#F4E7D4] mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-[#6D1919]">Artisan F&B</h4>
                            <p class="text-sm text-stone-600">Enjoy our menu of chef-crafted meals and refreshing artisan drinks, designed to be easy to eat while you play without compromising on flavor.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

<section class="py-24 bg-white">
    <div class="container mx-auto px-6">

        <div class="text-center mb-16 max-w-3xl mx-auto">
            <h3 class="text-[#BB9045] font-bold tracking-widest uppercase text-sm mb-3">
                Inventory Check
            </h3>
            <h2 class="text-4xl font-serif font-bold text-[#6D1919] mb-4">
                Choose Your Provisions
            </h2>
            <p class="text-gray-600 leading-relaxed">
                A true adventurer never travels on an empty stomach. From heavy meals to quick boosts,
                our inventory is stocked with everything you need to survive the next dungeon crawl.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">

            <div class="group bg-[#F4E7D4] rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 border-2 border-transparent hover:border-[#BB9045]">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&q=80" alt="Rice Bowls and Pastas" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-8">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-6 h-6 text-[#6D1919]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                        <h3 class="text-xl font-bold text-[#6D1919]">Campaign Feasts</h3>
                    </div>
                    <p class="text-[#BB9045] font-bold text-xs uppercase tracking-wide mb-2">Rice Bowls & Pastas</p>
                    <p class="text-gray-700 text-sm">
                        Hearty rations for the long haul. Whether you need a savory rice bowl to restore HP or rich pasta to prep for a boss fight, these meals are a critical hit for hunger.
                    </p>
                </div>
            </div>

            <div class="group bg-[#F4E7D4] rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 border-2 border-transparent hover:border-[#BB9045]">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=600&q=80" alt="Snacks and Finger Food" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-8">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-6 h-6 text-[#6D1919]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <h3 class="text-xl font-bold text-[#6D1919]">Table Loot</h3>
                    </div>
                    <p class="text-[#BB9045] font-bold text-xs uppercase tracking-wide mb-2">Small Bites & Sharing</p>
                    <p class="text-gray-700 text-sm">
                        Dangerous to go alone? Share these. Crispy, savory, and designed for clean fingers so you can munch on your turn without stalling the game.
                    </p>
                </div>
            </div>

            <div class="group bg-[#F4E7D4] rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 border-2 border-transparent hover:border-[#BB9045]">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80" alt="Coffee and Sodas" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-8">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-6 h-6 text-[#6D1919]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        <h3 class="text-xl font-bold text-[#6D1919]">Potions & Elixirs</h3>
                    </div>
                    <p class="text-[#BB9045] font-bold text-xs uppercase tracking-wide mb-2">Coffee & Sodas</p>
                    <p class="text-gray-700 text-sm">
                        Boost your Intelligence with our artisan coffee or refresh your Stamina with sparkling sodas. Essential buffs to keep your head in the game.
                    </p>
                </div>
            </div>

        </div>

        <div class="text-center">
            <a href="{{ route('menu.index') }}"
               class="inline-block px-10 py-3 bg-[#6D1919] text-[#F4E7D4] font-bold rounded shadow-lg hover:bg-[#BB9045] hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                View Full Menu
            </a>
        </div>

    </div>
</section>

    {{-- 3. INFO SECTION (Dark Strip) --}}
    <section class="bg-[#6D1919] text-[#F4E7D4] py-16">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-[#BB9045]/30">

            {{-- Hours --}}
            <div class="p-4">
                <svg class="w-10 h-10 text-[#BB9045] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-xl font-serif font-bold mb-2">Opening Hours</h3>
                <p class="opacity-80">Mon - Sun: 10:00 - 22:00</p>
            </div>

            {{-- Location --}}
            <div class="p-4 pt-8 md:pt-4">
                <svg class="w-10 h-10 text-[#BB9045] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <h3 class="text-xl font-serif font-bold mb-2">Location</h3>
                <p class="opacity-80">Ruko Ruby Commercial Estate, Jl. Bulevar Selatan Blok TD 09</p>
                <p class="opacity-80">Bekasi Utara, Jawa Barat</p>
            </div>

            {{-- Contact --}}
            <div class="p-4 pt-8 md:pt-4">
                <svg class="w-10 h-10 text-[#BB9045] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <h3 class="text-xl font-serif font-bold mb-2">Contact</h3>
                <p class="opacity-80">0851-1762-5516</p>
                <p class="opacity-80">natural20bgc@gmail.com</p>
            </div>
        </div>
    </section>

    {{-- 4. MINI GALLERY --}}
    <section class="py-20 bg-[#F4E7D4]">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-center text-3xl font-serif font-bold text-[#6D1919] mb-12">Inside The Venue</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Pic 1 --}}
                <div class="group relative overflow-hidden rounded-lg shadow-lg border border-[#BB9045] h-64">
                    <img src="https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&q=80" alt="Main Hall" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-[#F4E7D4] font-bold opacity-0 group-hover:opacity-100 transition duration-500 translate-y-4 group-hover:translate-y-0">
                        Main Hall
                    </div>
                </div>
                {{-- Pic 2 --}}
                <div class="group relative overflow-hidden rounded-lg shadow-lg border border-[#BB9045] h-64">
                    <img src="https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&q=80" alt="VIP Room" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-[#F4E7D4] font-bold opacity-0 group-hover:opacity-100 transition duration-500 translate-y-4 group-hover:translate-y-0">
                        VIP Room
                    </div>
                </div>
                {{-- Pic 3 --}}
                <div class="group relative overflow-hidden rounded-lg shadow-lg border border-[#BB9045] h-64">
                    <img src="https://images.unsplash.com/photo-1580327344181-c1163234e5a0?w=800&q=80" alt="D&D Setup" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-[#F4E7D4] font-bold opacity-0 group-hover:opacity-100 transition duration-500 translate-y-4 group-hover:translate-y-0">
                        Private D&D Setup
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. DUAL CTA (Library & Booking) --}}
    <section class="grid grid-cols-1 md:grid-cols-2">

        {{-- Left: Game Library --}}
        <div class="relative bg-stone-900 py-24 px-10 text-center group overflow-hidden">
            <div class="absolute inset-0 opacity-20 group-hover:opacity-30 transition duration-700 transform group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1566694271453-390536dd1f0d?w=1200&q=80'); background-size: cover; background-position: center;"></div>
            <div class="relative z-10">
                <h3 class="text-3xl font-serif font-bold text-[#BB9045] mb-4">The Grand Archives</h3>
                <p class="text-stone-300 mb-8 max-w-sm mx-auto">Explore our collection of 100+ board games, from Catan to Gloomhaven.</p>
                <a href="{{ route('games.index') }}" class="inline-block border-2 border-[#BB9045] text-[#BB9045] font-bold py-3 px-8 rounded hover:bg-[#BB9045] hover:text-[#6D1919] transition uppercase tracking-widest text-sm">
                    Browse Games
                </a>
            </div>
        </div>

        {{-- Right: Booking --}}
        <div class="relative bg-[#6D1919] py-24 px-10 text-center group overflow-hidden">
             <div class="absolute inset-0 opacity-20 group-hover:opacity-30 transition duration-700 transform group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1612404730960-5c71577fca11?w=1200&q=80'); background-size: cover; background-position: center;"></div>
            <div class="relative z-10">
                <h3 class="text-3xl font-serif font-bold text-[#F4E7D4] mb-4">Private Experience</h3>
                <p class="text-[#F4E7D4] opacity-90 mb-8 max-w-sm mx-auto">Secure a custom D&D room for your next session or private party.</p>
                <a href="{{ route('bookings.create') }}" class="inline-block bg-[#BB9045] text-[#6D1919] font-bold py-3 px-8 rounded hover:bg-[#F4E7D4] transition uppercase tracking-widest text-sm shadow-lg">
                    Book Now
                </a>
            </div>
        </div>

    </section>

@endsection
