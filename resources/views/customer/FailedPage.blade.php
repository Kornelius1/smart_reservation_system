@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="container mx-auto px-4 py-16 min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full bg-white shadow-xl rounded-lg p-8 md:p-12 text-center">
        
        {{-- Tampilan Gagal --}}
        <svg class="w-20 h-20 mx-auto text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h1 class="text-3xl font-bold text-error mt-4">Pembayaran Gagal</h1>
        <p class="text-gray-600 mt-2">{{ $message }}</p>
        <p class="text-gray-500 mt-1">Invoice: <span class="font-bold">{{ $invoice }}</span></p>

        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Apa yang harus dilakukan?</h2>
            
            <a href="{{ route('payment.show') }}" class="btn btn-warning w-full max-w-xs mb-3">
                Coba Pesan/Bayar Lagi
            </a>
            
            <a href="{{ route('customer.landing.page') }}" class="btn btn-outline w-full max-w-xs">
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection