@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-beige p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-semibold text-green-700">Dashboard Admin</h1>
    </div>
    <div class="grid grid-cols-4 gap-8">
        <a href="{{ route('manajemen.menu.index') }}" class="bg-green-400 rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
            <img src="{{ asset('images/icons/menu-icon.svg') }}" alt="Manajemen Menu" class="w-16 h-16 mb-4 opacity-50">
            <span class="text-white text-sm">Manajemen Menu</span>
        </a>
        <a href="{{ route('manajemen.meja.index') }}" class="bg-green-400 rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
            <img src="{{ asset('images/icons/table-icon.svg') }}" alt="Manajemen Meja" class="w-16 h-16 mb-4 opacity-50">
            <span class="text-white text-sm">Manajemen Meja</span>
        </a>
        <a href="{{ route('manajemen.reservasi.index') }}" class="bg-green-400 rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
            <img src="{{ asset('images/icons/reservation-icon.svg') }}" alt="Manajemen Reservasi" class="w-16 h-16 mb-4 opacity-50">
            <span class="text-white text-sm">Manajemen Reservasi</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="bg-green-400 rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
            <span class="text-white text-sm">Laporan</span>
        </a>
    </div>
</div>
@endsection
