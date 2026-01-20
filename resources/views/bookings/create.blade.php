@extends('layouts.app')

@section('title', 'Booking')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
        background: #6D1919 !important;
        border-color: #6D1919 !important;
    }
    .flatpickr-day.today {
        border-color: #BB9045 !important;
    }
    .flatpickr-day:hover {
        background: #F4E7D4 !important;
        border-color: #BB9045 !important;
    }
</style>

@endsection

@section('content')
    {{-- Hero Section --}}
    <div class="bg-[#6D1919] text-white py-16 px-6 text-center shadow-lg relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-[#BB9045] opacity-10 rounded-full blur-3xl -translate-y-1/2"></div>

        <div class="relative z-10">
            <h1 class="text-4xl md:text-6xl font-bold mb-3 tracking-wide text-[#F4E7D4]">Book Your Private Space</h1>
            <p class="text-[#EFDFAB] text-lg font-light tracking-wider uppercase">VIP Experiences & D&D Sessions</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto -mt-10 px-4 pb-12 relative z-20">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-[#EFDFAB] border-l-4 border-[#6D1919] text-[#6D1919] p-4 mb-6 shadow-md rounded-r flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-bold">Booking Request Sent!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-md rounded-r">
                <p class="font-bold">Please correct the errors below:</p>
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main Form Card --}}
        <div class="bg-white shadow-2xl rounded-xl overflow-hidden border-t-4 border-[#BB9045]">
            <div class="p-8">
                <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- 1. Contact Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-[#6D1919] uppercase tracking-wider mb-2">Your Name</label>
                            <input type="text" name="customer_name" class="w-full rounded-none border-b-2 border-[#F4E7D4] bg-stone-50 p-3 text-sm focus:border-[#BB9045] focus:ring-0 focus:outline-none transition-colors" placeholder="John Doe" required value="{{ old('customer_name') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#6D1919] uppercase tracking-wider mb-2">WhatsApp Number</label>
                            <input type="tel" name="customer_phone" class="w-full rounded-none border-b-2 border-[#F4E7D4] bg-stone-50 p-3 text-sm focus:border-[#BB9045] focus:ring-0 focus:outline-none transition-colors" placeholder="0812..." required value="{{ old('customer_phone') }}">
                        </div>
                    </div>

                    {{-- 2. Room & Date --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-[#6D1919] uppercase tracking-wider mb-2">Select Room</label>
                            <select name="room_id" id="room_select" class="w-full rounded border border-[#F4E7D4] bg-stone-50 p-3 text-sm focus:ring-2 focus:ring-[#BB9045] focus:border-[#BB9045] focus:outline-none" required>
                                <option value="" disabled selected>-- Choose Room --</option>
                                @foreach($rooms as $room)
                                    <option
                                        value="{{ $room->id }}"
                                        data-slug="{{ $room->slug }}"
                                        data-deposit="{{ $room->deposit }}"   {{-- NEW FIELD --}}
                                        data-base="{{ $room->base_cost }}"    {{-- Min Spend --}}
                                        data-person="{{ $room->person_cost }}"
                                    >
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="room_hint" class="text-xs text-[#BB9045] mt-1 italic font-medium"></p>
                        </div>

                        <div>
                            <div>
                                <label class="block text-xs font-bold text-[#6D1919] uppercase tracking-wider mb-2">Booking Date</label>
                                {{-- We switch type to "text" because Flatpickr handles the UI --}}
                                <input
                                    type="text"
                                    name="booking_date"
                                    id="datepicker"
                                    class="w-full rounded border border-[#F4E7D4] bg-stone-50 p-3 text-sm focus:ring-2 focus:ring-[#BB9045] focus:outline-none cursor-pointer"
                                    required
                                    placeholder="Select Date..."
                                    readonly
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#6D1919] uppercase tracking-wider mb-2">Arrival Time (ETA)</label>
                            <input type="time" name="eta" class="w-full rounded border border-[#F4E7D4] bg-stone-50 p-3 text-sm focus:ring-2 focus:ring-[#BB9045] focus:outline-none" required value="{{ old('eta') }}">
                        </div>
                    </div>

                    {{-- 3. D&D Specifics (The "Other" Color Theme) --}}
                    <div id="dnd_fields" class="hidden bg-[#EFDFAB] bg-opacity-40 p-6 rounded-lg mb-8 border border-[#BB9045] relative">

                        <div class="flex items-center mb-4">
                            <h3 class="font-bold text-[#6D1919] text-lg flex items-center mr-2">
                                <svg class="w-6 h-6 mr-2 text-[#BB9045]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                D&D Party Details
                            </h3>
                            {{-- Tooltip --}}
                            <div class="relative group">
                                <button type="button" class="w-5 h-5 rounded-full bg-[#BB9045] text-white text-xs font-bold flex items-center justify-center cursor-help">?</button>
                                <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-64 hidden group-hover:block z-10">
                                    <div class="bg-[#6D1919] text-[#F4E7D4] text-xs rounded py-2 px-3 shadow-lg relative text-center">
                                        To confirm this booking, you must transfer a <strong>deposit of Rp 85.000</strong>.
                                        <div class="absolute w-3 h-3 bg-[#6D1919] transform rotate-45 left-1/2 -translate-x-1/2 -bottom-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-[#6D1919] text-sm font-bold mb-2">Total Persons</label>
                            <input type="number" name="total_person" id="total_person" class="w-full bg-white border border-[#BB9045] rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#6D1919] focus:outline-none placeholder-[#BB9045]" placeholder="e.g. 5" min="1" value="{{ old('total_person') }}">
                            <p class="text-xs text-[#6D1919] mt-1 font-semibold">Price: Rp 85.000,- per person</p>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-[#6D1919] text-sm font-bold mb-2">Notes</label>
                            <input type="text" class="w-full bg-white border border-[#BB9045] rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#6D1919] focus:outline-none placeholder-[#BB9045]" name="notes" value="{{ old('notes') }}">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="need_dm" id="need_dm" value="1" class="w-4 h-4 text-[#6D1919] bg-white border-[#BB9045] rounded focus:ring-[#BB9045]" {{ old('need_dm') ? 'checked' : '' }}>
                            <label for="need_dm" class="ml-2 text-[#6D1919] text-sm font-bold">We need a Dungeon Master (DM)</label>
                        </div>
                    </div>

                    {{-- 3.5 Payment Information Card --}}
                    <div class="bg-[#F4E7D4] border border-[#BB9045] rounded-lg p-5 mb-8">
                        <h3 class="text-xs font-bold text-[#6D1919] uppercase tracking-wide mb-3">Transfer to Bank Account</h3>

                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <div class="bg-white p-3 rounded border border-[#EFDFAB] w-full md:w-auto text-center shadow-sm">
                                <span class="font-bold text-xl text-[#6D1919]">{{ $bank['name'] ?? 'BCA' }}</span>
                            </div>

                            <div class="grow text-center md:text-left">
                                <p class="text-xs text-[#BB9045] font-bold">Account Number</p>
                                <div class="flex items-center justify-center md:justify-start gap-2">
                                    <span id="bank_number" class="text-xl font-mono font-bold text-[#6D1919]">{{ $bank['number'] ?? '1234 5678 90' }}</span>
                                    <button type="button" onclick="copyToClipboard()" class="text-[#BB9045] hover:text-[#6D1919] transition p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                                <p class="text-sm font-medium text-stone-600">{{ $bank['holder'] ?? 'Your Cafe' }}</p>
                            </div>
                        </div>
                        <p id="copy_feedback" class="text-xs text-[#6D1919] font-bold mt-2 hidden text-center md:text-left">✓ Copied!</p>
                    </div>

                    {{-- 4. Payment Proof --}}
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-[#6D1919] uppercase tracking-wider mb-2">Upload Transfer Proof</label>
                        <div class="border-2 border-dashed border-[#BB9045] rounded-lg h-48 relative overflow-hidden hover:bg-[#EFDFAB] hover:bg-opacity-30 transition group bg-[#F4E7D4] bg-opacity-20">
                            <input type="file" name="payment_proof" id="payment_proof_input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*">

                            <div id="upload_placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-[#BB9045] pointer-events-none z-10 transition-opacity duration-300">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-sm font-bold">Click to upload screenshot</p>
                                <p class="text-xs mt-1 text-stone-500">JPG, PNG (Max 2MB)</p>
                            </div>

                            <img id="image_preview" class="absolute inset-0 w-full h-full object-contain hidden z-10 p-2" />
                            <div id="change_badge" class="absolute bottom-0 left-0 right-0 bg-[#6D1919] text-[#F4E7D4] text-xs text-center py-2 hidden z-10">Click to change image</div>
                        </div>
                    </div>

                    {{-- 5. Deposit & Total Section (Redesigned) --}}
                    <div class="bg-[#F4E7D4] p-6 rounded-lg border-2 border-[#BB9045] shadow-xl relative overflow-hidden group">

                        {{-- Decorative Background Icon --}}
                        <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-[#BB9045] opacity-10 transform rotate-12 group-hover:rotate-0 transition-transform duration-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>

                        <div class="flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">

                            {{-- Left Side: The Numbers --}}
                            <div class="text-center md:text-left w-full md:w-auto">

                                {{-- 1. The Big Deposit (Pay Now) --}}
                                <div class="mb-2">
                                    <p class="text-xs text-[#6D1919] uppercase tracking-widest font-bold mb-1">
                                        Please transfer a deposit (DP) of:
                                    </p>
                                    <p class="text-4xl md:text-5xl font-extrabold text-[#6D1919] tracking-tight" id="deposit_display">
                                        Rp 0
                                    </p>
                                </div>

                                {{-- 2. The Estimated Total (Pay Later) --}}
                                <div class="inline-block bg-[#6D1919]/10 rounded px-2 py-1">
                                    <p class="text-sm font-semibold text-[#6D1919]/90">
                                        Est. Final Bill: <span id="total_display">Rp 0</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Right Side: The Button --}}
                            <button type="submit" class="bg-[#6D1919] hover:bg-[#521313] text-[#F4E7D4] font-bold py-4 px-12 rounded shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 w-full md:w-auto tracking-widest uppercase text-sm flex flex-col items-center justify-center">
                                <span>Confirm Booking</span>
                                <span class="text-[10px] opacity-70 normal-case font-normal mt-1">Upload proof to lock date</span>
                            </button>
                        </div>

                        {{-- Disclaimer --}}
                        <div class="border-t border-[#BB9045] border-opacity-30 pt-3 mt-4 text-center md:text-left">
                            <p class="text-[11px] italic text-[#6D1919]/80">
                                * The <strong>Deposit</strong> is deducted from your final bill. It is non-refundable if cancelled.
                            </p>
                            <p class="text-[11px] italic text-[#6D1919]/80">
                                * Rescheduling request must be made at least 24 hours prior to your booking.
                            </p>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- Javascript Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookedDates = @json($bookedDates);
            const roomSelect = document.getElementById('room_select');

            const fp = flatpickr("#datepicker", {
                disableMobile: "true",
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: [], // Initially empty until room is picked
                onChange: function(selectedDates, dateStr, instance) {
                    // Optional: You can do something when date is picked
                }
            });

            roomSelect.addEventListener('change', function() {
                const roomId = this.value;

                // Clear the current date if it conflicts
                fp.clear();

                if (bookedDates[roomId]) {
                    // If this room has bookings, disable them in the calendar
                    fp.set('disable', bookedDates[roomId]);
                } else {
                    // If no bookings, make everything available
                    fp.set('disable', []);
                }
            });

            // Just copying the logic from before for clarity:
            const dndFields = document.getElementById('dnd_fields');
            const personInput = document.getElementById('total_person');
            const priceDisplay = document.getElementById('price_display');
            const roomHint = document.getElementById('room_hint');
            const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

            function updateCalculator() {
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];

                // UI Elements
                const depositDisplay = document.getElementById('deposit_display');
                const totalDisplay = document.getElementById('total_display');

                if (!selectedOption.value) {
                    depositDisplay.innerText = formatRupiah(0);
                    totalDisplay.innerText = formatRupiah(0);
                    return;
                }

                // Get Data
                const slug = selectedOption.getAttribute('data-slug');
                const deposit = parseFloat(selectedOption.getAttribute('data-deposit'));
                const baseCost = parseFloat(selectedOption.getAttribute('data-base'));
                const personCost = parseFloat(selectedOption.getAttribute('data-person'));

                let estimatedTotal = 0;

                // --- LOGIC START ---

                // 1. D&D Room logic
                if (slug === 'dnd') {
                    dndFields.classList.remove('hidden');
                    personInput.setAttribute('required', 'required');

                    // Total = Persons * 85k
                    const count = parseInt(personInput.value) || 0;
                    estimatedTotal = count * personCost;

                    // If total is 0 (no input), show "Depends on pax"
                    if(count === 0) estimatedTotal = 0;
                }
                // 2. VIP Room logic
                else {
                    dndFields.classList.add('hidden');
                    personInput.removeAttribute('required');
                    personInput.value = ''; // Reset

                    // Total = Minimum Spend (700k)
                    estimatedTotal = baseCost;
                }

                // --- UPDATE DISPLAY ---

                // Deposit is always fixed based on room (85k or 100k)
                depositDisplay.innerText = formatRupiah(deposit);

                // Total Display
                if (estimatedTotal > 0) {
                    totalDisplay.innerText = formatRupiah(estimatedTotal);
                } else {
                    totalDisplay.innerText = "Calculated at venue";
                }
            }

            roomSelect.addEventListener('change', updateCalculator);
            personInput.addEventListener('input', updateCalculator);
            updateCalculator();

            // Image Preview Logic
            const fileInput = document.getElementById('payment_proof_input');
            const placeholder = document.getElementById('upload_placeholder');
            const preview = document.getElementById('image_preview');
            const changeBadge = document.getElementById('change_badge');

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        changeBadge.classList.remove('hidden');
                        placeholder.classList.add('opacity-0');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Fallback Copy Logic
            window.copyToClipboard = function() {
                const numberText = document.getElementById('bank_number').innerText.replace(/\s/g, '');
                const feedback = document.getElementById('copy_feedback');

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(numberText).then(showFeedback);
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = numberText;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-9999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        showFeedback();
                    } catch (err) {
                        alert('Manual Copy: ' + numberText);
                    }
                    document.body.removeChild(textArea);
                }

                function showFeedback() {
                    feedback.classList.remove('hidden');
                    setTimeout(() => feedback.classList.add('hidden'), 2000);
                }
            }
        });
    </script>
@endsection
