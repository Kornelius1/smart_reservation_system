@extends('layouts.app')

@section('title', 'Pembayaran Sukses')

@section('content')
    <div class="container mx-auto px-4 py-16 min-h-screen flex items-center justify-center">
        <div class="max-w-xl w-full bg-white shadow-xl rounded-lg p-8 md:p-12 text-center">

            @if ($isSuccess)
                {{-- Tampilan Sukses --}}
                <svg class="w-20 h-20 mx-auto text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="text-3xl font-bold text-success mt-4">Pembayaran Berhasil!</h1>
                <p class="text-gray-600 mt-2">{{ $statusMessage }}</p>

                <div class="mt-8 border rounded-lg p-4 bg-gray-50 text-left">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-3">Detail Reservasi</h2>
                    <p class="text-gray-700 mb-1">
                        <span class="font-medium w-36 inline-block">Nomor Invoice:</span>
                        <span class="font-bold text-lg text-primary">{{ $reservation->id_transaksi }}</span>
                    </p>
                    <p class="text-gray-700 mb-1">
                        <span class="font-medium w-36 inline-block">Nama Pemesan:</span>
                        {{ $reservation->nama }}
                    </p>
                    <p class="text-gray-700 mb-1">
                        <span class="font-medium w-36 inline-block">Total Pembayaran:</span>
                        <span class="font-bold text-xl text-success">Rp
                            {{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                    </p>
                    <p class="text-gray-700">
                        <span class="font-medium w-36 inline-block">Waktu:</span>
                        {{-- [PERBAIKAN] Gabungkan tanggal (Objek) dan waktu (String) --}}
                        {{ \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->waktu)->format('d F Y, H:i') }}
                        WIB
                    </p>
                </div>
            @else
                {{-- Tampilan Jika Status Masih Pending --}}
                <svg class="w-20 h-20 mx-auto text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.332 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <h1 class="text-3xl font-bold text-warning mt-4">Sedang Diproses</h1>
                <p class="text-gray-600 mt-2">{{ $statusMessage }}</p>
            @endif


            <a href="{{ route('customer.landing.page') }}" class="btn btn-primary mt-8 w-full max-w-xs">
                Kembali ke Beranda
            </a>

            {{-- Tombol Download Struk Anda (Sudah benar) --}}
            <a href="{{ route('payment.receipt', ['invoice' => $reservation->id_transaksi]) }}"
                class="btn btn-outline-primary mt-4 w-full max-w-xs" target="_blank">
                Download Struk (PDF)
            </a>
        </div>
    </div>
@endsection