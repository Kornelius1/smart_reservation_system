@extends('layouts.guest')

@section('title', 'Reservasi Ruangan')

@section('header')
    <header class="bg-dark-green text-white p-4 text-center text-lg font-bold">
        Pilih Ruangan Anda!
    </header>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-5xl">

        <div
            class="bg-amber-50 rounded-3xl p-6 shadow-md text-center transition-transform duration-200 ease-in-out hover:scale-105">
            <img src="{{ asset('images/indoor1.png') }}" alt="Indoor 1" class="rounded-lg mb-4 w-full h-48 object-cover">
            <h2 class="text-xl font-bold mb-2">Indoor 1 - Cafe Area</h2>
            <div class="text-sm space-y-1 text-left mx-auto max-w-max mb-4">
                <p>👥 Kapasitas: 20 Orang</p>
                <p>💰 Min. Order: Rp200.000 / 3 jam</p>
                <p>⏱ Extra Time: Rp50.000 / jam</p>
            </div>
            <a href="/pesanmenu?room_name=Ruangan+1&min_order=200000" class="btn-custom inline-block">Pilih Ruangan Ini</a>
        </div>

        <div
            class="bg-amber-50 rounded-3xl p-6 shadow-md text-center transition-transform duration-200 ease-in-out hover:scale-105">
            <img src="{{ asset('images/indoor2.png') }}" alt="Indoor 2" class="rounded-lg mb-4 w-full h-48 object-cover">
            <h2 class="text-xl font-bold mb-2">Indoor 2 - Private Hall</h2>
            <div class="text-sm space-y-1 text-left mx-auto max-w-max mb-4">
                <p>👥 Kapasitas: 35 Orang</p>
                <p>💰 Min. Order: Rp400.000 / 3 jam</p>
                <p>⏱ Extra Time: Rp500.000 / jam</p>
            </div>
            <a href="/pesanmenu?room_name=Ruangan+2&min_order=400000" class="btn-custom inline-block">Pilih Ruangan Ini</a>
        </div>

    </div>
@endsection