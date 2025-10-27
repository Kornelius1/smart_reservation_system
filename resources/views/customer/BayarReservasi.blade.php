<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="min-h-screen bg-[#F8F4E8] flex items-center justify-center p-4 font-sans">
        <div class="bg-[#788869] text-white p-8 rounded-lg shadow-xl w-full max-w-md">

            {{-- 1. BLOK ERROR (Tidak berubah) --}}
            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{-- Tampilkan error pertama yang ditemukan --}}
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- 2. FORM ACTION (Tidak berubah) --}}
            <form method="POST" action="{{ route('payment.process') }}">
                @csrf

                {{-- INPUT HIDDEN (Tidak berubah) --}}
                @foreach ($cartItems as $item)
                    <input type="hidden" name="items[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
                @endforeach
                @if(isset($reservationDetails))
                    @if($reservationDetails['type'] === 'ruangan')
                        <input type="hidden" name="reservation_room_name" value="{{ $reservationDetails['detail'] }}">
                    @elseif($reservationDetails['type'] === 'meja')
                        <input type="hidden" name="reservation_table_number" value="{{ $reservationDetails['detail'] }}">
                    @endif
                @endif
                {{-- END INPUT HIDDEN --}}

                <h1 class="text-3xl font-bold text-center mb-2">Konfirmasi Pesanan Anda</h1>

                {{-- DETAIL RESERVASI (Tidak berubah) --}}
                @if (isset($reservationDetails))
                    <div class="mb-6 p-3 bg-white/10 rounded-lg text-center">
                        <p class="font-semibold text-sm">
                            @if($reservationDetails['type'] === 'ruangan')
                                Reservasi untuk: {{ $reservationDetails['detail'] }}
                            @elseif($reservationDetails['type'] === 'meja')
                                Reservasi untuk: Meja {{ $reservationDetails['detail'] }}
                            @endif
                        </p>
                    </div>
                @endif

                {{-- DETAIL PESANAN (Tidak berubah) --}}
                <h2 class="text-lg font-semibold mb-2">Detail Pesanan</h2>
                <div class="space-y-2 text-sm">
                    @foreach ($cartItems as $item)
                        <div class="grid grid-cols-3 gap-2 items-center">
                            <span>{{ $item['name'] }}</span>
                            <span class="text-center">{{ $item['quantity'] }} x Rp {{ number_format($item['price']) }}</span>
                            <span class="text-right font-medium">Rp {{ number_format($item['subtotal']) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="divider before:bg-white/25 after:bg-white/25 my-6"></div>

                {{-- ========================================================== --}}
                {{-- BARU: DETAIL PEMESAN (Sesuai model Reservation) --}}
                {{-- ========================================================== --}}
                <h2 class="text-lg font-semibold mb-4">Detail Pemesan</h2>
                <div class="space-y-4">
                    {{-- 1. Nama Customer --}}
                    <div>
                        <label for="nama" class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required 
                               class="input input-bordered w-full bg-white/20 placeholder:text-white/50">
                    </div>

                    {{-- 2. Nomor Telepon --}}
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium mb-1">Nomor Telepon (WA)</label>
                        <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required
                               class="input input-bordered w-full bg-white/20 placeholder:text-white/50" placeholder="08...">
                    </div>

                    {{-- 3. Jumlah Orang --}}
                    <div>
                        <label for="jumlah_orang" class="block text-sm font-medium mb-1">Jumlah Orang</label>
                        <input type="number" id="jumlah_orang" name="jumlah_orang" value="{{ old('jumlah_orang') }}" required min="1"
                               class="input input-bordered w-full bg-white/20 placeholder:text-white/50">
                    </div>

                    {{-- 4. Tanggal & Waktu --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required
                                   class="input input-bordered w-full bg-white/20">
                        </div>
                        <div>
                            <label for="waktu" class="block text-sm font-medium mb-1">Waktu</p>
                            <input type="time" id="waktu" name="waktu" value="{{ old('waktu') }}" required
                                   class="input input-bordered w-full bg-white/20">
                        </div>
                    </div>
                </div>
                {{-- ========================================================== --}}
                {{-- AKHIR BLOK BARU --}}
                {{-- ========================================================== --}}

                <div class="divider before:bg-white/25 after:bg-white/25 my-6"></div>

                {{-- RINCIAN PEMBAYARAN (Tidak berubah) --}}
                <div class="flex justify-between font-bold text-base">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($totalPrice) }}</span>
                </div>

                {{-- TOMBOL SUBMIT (Tidak berubah) --}}
                <div class="mt-8">
                    <button type="submit" class="btn bg-[#364132] hover:bg-[#2a3327] border-none text-white w-full">
                        Bayar Sekarang 🛒
                    </button>
                </div>
            </form>

            {{-- TOMBOL KEMBALI (Tidak berubah) --}}
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="text-sm text-white/70 hover:text-white">
                    Kembali & Ubah Pesanan
                </a>
            </div>
        </div>
    </div>
</body>
</html>