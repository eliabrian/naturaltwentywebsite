<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | Natural Twenty Board Game Cafe</title>
    <link rel="icon" href="{{ asset('storage/favicon/favicon.ico') }}" type="image/png">

    <link rel="icon" type="image/png" href="{{ asset('storage/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('storage/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('storage/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('storage/favicon/site.webmanifest') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
    @yield('styles')
</head>
<body class="bg-[#F4E7D4] text-stone-800 antialiased min-h-screen flex flex-col">
    {{-- Navigation --}}
    <nav class="bg-[#6D1919] text-[#F4E7D4] shadow-lg border-b-4 border-[#BB9045] sticky top-0 z-50" x-data="{ isOpen: false }">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-24">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        {{-- Logo Image --}}
                        <img src="{{ asset('storage/logo/icon.png') }}" alt="Cafe Logo" class="h-18 w-auto object-contain group-hover:opacity-90 transition">
                    </a>
                </div>

                {{-- 2. DESKTOP MENU (Hidden on Mobile) --}}
                <div class="hidden md:flex space-x-8 text-sm font-bold uppercase tracking-wider items-center">
                    <a href="{{ route('home') }}"
                    class="py-1 border-b-2 transition duration-300 {{ request()->routeIs('home') ? 'text-[#BB9045] border-[#BB9045]' : 'text-[#F4E7D4] border-transparent hover:text-[#BB9045] hover:border-[#BB9045]' }}">
                        Home
                    </a>

                    {{-- Our Menu Link --}}
                    <a href="{{ route('menu.index') }}"
                    class="py-1 border-b-2 transition duration-300 {{ request()->routeIs('menu.*') ? 'text-[#BB9045] border-[#BB9045]' : 'text-[#F4E7D4] border-transparent hover:text-[#BB9045] hover:border-[#BB9045]' }}">
                        Our Menu
                    </a>

                    {{-- Book Room Link --}}
                    <a href="{{ route('bookings.create') }}"
                    class="py-1 border-b-2 transition duration-300 {{ request()->routeIs('bookings.*') ? 'text-[#BB9045] border-[#BB9045]' : 'text-[#F4E7D4] border-transparent hover:text-[#BB9045] hover:border-[#BB9045]' }}">
                        Book Room
                    </a>

                    {{-- Game Library Link --}}
                    <a href="{{ route('games.index') }}"
                    class="py-1 border-b-2 transition duration-300 {{ request()->routeIs('games.*') ? 'text-[#BB9045] border-[#BB9045]' : 'text-[#F4E7D4] border-transparent hover:text-[#BB9045] hover:border-[#BB9045]' }}">
                        Game Library
                    </a>
                </div>

                {{-- 3. MOBILE HAMBURGER BUTTON (Visible only on Mobile) --}}
                <div class="flex items-center md:hidden">
                    <button @click="isOpen = !isOpen" type="button" class="text-[#BB9045] hover:text-[#F4E7D4] focus:outline-none p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {{-- Icon when menu is CLOSED (Hamburger) --}}
                            <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            {{-- Icon when menu is OPEN (X) --}}
                            <path x-show="isOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- 4. MOBILE MENU DROPDOWN (Slides down when isOpen is true) --}}
        <div x-show="isOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden bg-[#521313] border-t border-[#BB9045]"
            x-cloak>

            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                {{-- Mobile Home --}}
                <a href="{{ route('home') }}"
                class="block px-3 py-2 rounded-md text-base font-bold uppercase tracking-wider transition {{ request()->routeIs('home') ? 'text-[#BB9045] bg-[#6D1919] border-l-4 border-[#BB9045]' : 'text-[#F4E7D4] hover:text-[#BB9045] hover:bg-[#6D1919]' }}">
                    Home
                </a>

                {{-- Mobile Menu --}}
                <a href="{{ route('menu.index') }}"
                class="block px-3 py-2 rounded-md text-base font-bold uppercase tracking-wider transition {{ request()->routeIs('menu.*') ? 'text-[#BB9045] bg-[#6D1919] border-l-4 border-[#BB9045]' : 'text-[#F4E7D4] hover:text-[#BB9045] hover:bg-[#6D1919]' }}">
                    Our Menu
                </a>

                {{-- Mobile Book Room --}}
                <a href="{{ route('bookings.create') }}"
                class="block px-3 py-2 rounded-md text-base font-bold uppercase tracking-wider transition {{ request()->routeIs('bookings.*') ? 'text-[#BB9045] bg-[#6D1919] border-l-4 border-[#BB9045]' : 'text-[#F4E7D4] hover:text-[#BB9045] hover:bg-[#6D1919]' }}">
                    Book Room
                </a>

                {{-- Mobile Game Library --}}
                <a href="{{ route('games.index') }}"
                class="block px-3 py-2 rounded-md text-base font-bold uppercase tracking-wider transition {{ request()->routeIs('games.*') ? 'text-[#BB9045] bg-[#6D1919] border-l-4 border-[#BB9045]' : 'text-[#F4E7D4] hover:text-[#BB9045] hover:bg-[#6D1919]' }}">
                    Game Library
                </a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-[#6D1919] text-[#F4E7D4] border-t-8 border-[#BB9045] mt-auto">

        {{-- Main Footer Content --}}
        <div class="max-w-6xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                {{-- Column 1: Brand & Tagline --}}
                <div class="text-center md:text-left">
                    {{-- Logo --}}
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                        <img src="{{ asset('storage/logo/icon.png') }}" alt="Logo" class="h-12 w-auto object-contain bg-white/10 rounded-full p-1">
                        <span class="font-serif font-bold text-2xl tracking-widest text-[#BB9045]">Natural Twenty Board Game</span>
                    </div>
                    <p class="text-sm opacity-80 leading-relaxed max-w-xs mx-auto md:mx-0">
                        The premier destination for immersive board gaming and VIP private experiences.
                        Roll the dice, sip some coffee, and create memories.
                    </p>
                </div>

                {{-- Column 2: Visit Us (Location & Hours) --}}
                <div class="text-center md:text-left">
                    <h3 class="font-bold text-[#BB9045] uppercase tracking-widest mb-4 text-sm border-b border-[#BB9045]/30 pb-2 inline-block md:block">
                        Visit Us
                    </h3>

                    {{-- Address --}}
                    <div class="flex flex-col gap-3 text-sm">
                        <div class="flex items-start justify-center md:justify-start gap-3">
                            <svg class="w-5 h-5 text-[#BB9045] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <p class="font-bold">Ruko Ruby Commercial Estate, Jl. Bulevar Selatan Blok TD 09</p>
                                <p class="opacity-80">Marga Mulya, Kec. Bekasi Utara, Kota Bks, Jawa Barat 17143</p>
                                <a href="https://www.google.com/maps?q=Natural%20Twenty%20Board%20Game%20Cafe" target="_blank" class="text-[#BB9045] hover:text-white underline text-xs mt-1 inline-block">
                                    Get Directions &rarr;
                                </a>
                            </div>
                        </div>

                        {{-- Hours --}}
                        <div class="flex items-start justify-center md:justify-start gap-3 mt-2">
                            <svg class="w-5 h-5 text-[#BB9045] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p><span class="opacity-60 w-12">Mon-Sun:</span> 10:00 - 22:00</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 3: Contact & Socials --}}
                <div class="text-center md:text-right">
                    <h3 class="font-bold text-[#BB9045] uppercase tracking-widest mb-4 text-sm border-b border-[#BB9045]/30 pb-2 inline-block md:block">
                        Contact & Connect
                    </h3>

                    <div class="flex flex-col gap-4 items-center md:items-end">

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/6281234567890" target="_blank" class="group flex items-center gap-3 hover:opacity-100 transition opacity-90">
                            <div class="text-right hidden md:block">
                                <p class="text-[10px] text-[#BB9045] font-bold uppercase tracking-wider">WhatsApp</p>
                                <p class="font-mono text-sm text-white">0851-1762-5516</p>
                            </div>
                            {{-- Icon --}}
                            <div class="w-9 h-9 rounded-full border border-[#BB9045] flex items-center justify-center text-[#BB9045] group-hover:bg-[#BB9045] group-hover:text-[#6D1919] transition duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </div>
                            {{-- Mobile Only Label (appears next to icon on small screens) --}}
                            <div class="md:hidden text-left">
                                <p class="font-mono text-sm">+62 851-1762-5516</p>
                            </div>
                        </a>

                        {{-- Email --}}
                        <a href="mailto:natural20bgc@gmail.com" class="group flex items-center gap-3 hover:opacity-100 transition opacity-90">
                            <div class="text-right hidden md:block">
                                <p class="text-[10px] text-[#BB9045] font-bold uppercase tracking-wider">Email Us</p>
                                <p class="font-sans text-sm text-white">natural20bgc@gmail.com</p>
                            </div>
                            <div class="w-9 h-9 rounded-full border border-[#BB9045] flex items-center justify-center text-[#BB9045] group-hover:bg-[#BB9045] group-hover:text-[#6D1919] transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="md:hidden text-left">
                                <p class="font-sans text-sm">natural20bgc@gmail.com</p>
                            </div>
                        </a>

                        {{-- Instagram --}}
                        <a href="https://instagram.com/natural20bgc" target="_blank" class="group flex items-center gap-3 hover:opacity-100 transition opacity-90">
                            <div class="text-right hidden md:block">
                                <p class="text-[10px] text-[#BB9045] font-bold uppercase tracking-wider">Instagram</p>
                                <p class="font-sans text-sm text-white">natural20bgc</p>
                            </div>
                            <div class="w-9 h-9 rounded-full border border-[#BB9045] flex items-center justify-center text-[#BB9045] group-hover:bg-[#BB9045] group-hover:text-[#6D1919] transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="md:hidden text-left">
                                <p class="font-sans text-sm">natural20bgc</p>
                            </div>
                        </a>

                        {{-- TikTok --}}
                        <a href="https://tiktok.com/@natural20bgc" target="_blank" class="group flex items-center gap-3 hover:opacity-100 transition opacity-90">
                            <div class="text-right hidden md:block">
                                <p class="text-[10px] text-[#BB9045] font-bold uppercase tracking-wider">TikTok</p>
                                <p class="font-sans text-sm text-white">natural20bgc</p>
                            </div>
                            <div class="w-9 h-9 rounded-full border border-[#BB9045] flex items-center justify-center text-[#BB9045] group-hover:bg-[#BB9045] group-hover:text-[#6D1919] transition duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                            </div>
                            <div class="md:hidden text-left">
                                <p class="font-sans text-sm">natural20bgc</p>
                            </div>
                        </a>

                    </div>
                </div>

            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="bg-[#521313] py-4 text-center border-t border-[#BB9045]/20">
            <p class="text-xs opacity-60 uppercase tracking-widest text-[#BB9045]">
                &copy; {{ date('Y') }} PT AETHERWYN FANTASIA INDONESIA. All Rights Reserved.
            </p>
        </div>
    </footer>

    @yield('scripts')
    @vite('resources/js/app.js')
</body>
</html>
