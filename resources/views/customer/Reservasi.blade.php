@extends('layouts.guest')


@section('title', 'Pilih Reservasi')


@section('header')
    <header class="bg-dark-green text-white p-4 text-center text-lg font-bold">
        Silahkan Pilih Reservasi Anda!
    </header>
@endsection


@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl">

        <div
            class="bg-amber-50 rounded-3xl p-8 shadow-md text-center transition-transform duration-200 ease-in-out hover:scale-[1.02]">
            <img src="{{ asset('../images/meja.svg') }}" alt="Reservasi Meja" class="mx-auto w-40 h-48 mb-6">
            <h2 class="text-2xl font-bold mb-4">Reservasi Meja</h2>
            <p class="mb-6">Pilih reservasi meja biasa sesuai jumlah kursi yang tersedia.</p>
            <a href="{{ url('/pilih-meja') }}" class="btn-custom inline-block">Pilih</a>
        </div>

        <div
            class="bg-amber-50 rounded-3xl p-8 shadow-md text-center transition-transform duration-200 ease-in-out hover:scale-[1.02]">
            <img src="{{ asset('../images/private.svg') }}" alt="Private Room" class="mx-auto w-40 h-48 mb-6">
            <h2 class="text-2xl font-bold mb-4">Private Room</h2>
            <p class="mb-6">Pesan ruangan khusus dengan privasi penuh untuk acara Anda.</p>
            <a href="{{ url('/reservasi-ruangan') }}" class="btn-custom inline-block">Pilih</a>
        </div>

    </div>
@endsection