<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    @vite('resources/css/app.css') {{-- Cara modern me-load CSS --}}
</head>

<body>
    <div class="min-h-screen bg-[#F8F4E8] flex items-center justify-center p-4 font-sans">
        <div class="bg-[#788869] text-white p-8 rounded-lg shadow-xl w-full max-w-md">

            {{-- 1. BLOK UNTUK MENAMPILKAN ERROR --}}
            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first('msg') }}</span> {{-- Tampilkan pesan error custom kita --}}
                </div>
            @endif

            {{-- 2. UBAH FORM ACTION KE RUTE PROSES PEMBAYARAN --}}
            <form method="POST" action="{{ route('payment.process') }}">
                @csrf

                {{-- Kirim kembali data penting secara tersembunyi untuk validasi ulang --}}
                @foreach ($cartItems as $item)
                    <input type="hidden" name="items[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
                @endforeach
                @if(isset($reservationDetails))
                    <input type="hidden" name="reservation_room_name" value="{{ $reservationDetails['room_name'] }}">
                @endif

                <h1 class="text-3xl font-bold text-center mb-2">Konfirmasi Pesanan Anda</h1>

                {{-- Tampilkan detail reservasi untuk kejelasan pengguna --}}
                @if (isset($reservationDetails))
                    <div class="mb-6 p-3 bg-white/10 rounded-lg text-center">
                        <p class="font-semibold text-sm">Reservasi untuk: {{ $reservationDetails['room_name'] }}</p>
                    </div>
                @endif

                {{-- Detail Pesanan (tidak berubah) --}}
                <h2 class="text-lg font-semibold mb-2">Detail Pesanan</h2>
                <div class="space-y-2 text-sm">
                    @foreach ($cartItems as $item)
                        <div class="grid grid-cols-3 gap-2 items-center">
                            <span>{{ $item['name'] }}</span>
                            <span class="text-center">{{ $item['quantity'] }} x Rp
                                {{ number_format($item['price']) }}</span>
                            <span class="text-right font-medium">Rp {{ number_format($item['subtotal']) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="divider before:bg-white/25 after:bg-white/25 my-6"></div>

                {{-- Rincian Pembayaran (tidak berubah) --}}
                <div class="flex justify-between font-bold text-base">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($totalPrice) }}</span>
                </div>

                <div class="mt-8">
                    <button type="submit" class="btn bg-[#364132] hover:bg-[#2a3327] border-none text-white w-full">
                        Bayar Sekarang 🛒
                    </button>
                </div>
            </form>

            {{-- Tombol untuk kembali jika pengguna ingin mengubah pesanan --}}
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="text-sm text-white/70 hover:text-white">
                    Kembali & Ubah Pesanan
                </a>
            </div>
        </div>
    </div>
</body>

</html>