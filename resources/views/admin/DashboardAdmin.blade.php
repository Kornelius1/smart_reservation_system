@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-beige p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-semibold text-brand-primary">Dashboard Admin</h1>
        </div>
        <div class="grid grid-cols-4 gap-8">
            <a href="{{ route('manajemen-menu') }}"
                class="btn-gradient rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
                <img src="{{ asset('images/icons/menu-icon.svg') }}" alt="Manajemen Menu" class="w-16 h-16 mb-4 opacity-50">
                <span class="text-white text-lg">Manajemen Menu</span>
            </a>
            <a href="{{ route('manajemen-meja') }}"
                class="btn-gradient rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
                <img src="{{ asset('images/icons/table-icon.svg') }}" alt="Manajemen Meja"
                    class="w-16 h-16 mb-4 opacity-50">
                <span class="text-white text-lg">Manajemen Meja</span>
            </a>
            <a href="{{ route('manajemen-reservasi') }}"
                class="btn-gradient rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
                <img src="{{ asset('images/icons/reservation-icon.svg') }}" alt="Manajemen Reservasi"
                    class="w-16 h-16 mb-4 opacity-50">
                <span class="text-white text-lg">Manajemen Reservasi</span>
            </a>
            <a href="{{ route('manajemen-reschedule') }}"
                class="btn-gradient rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
                <img src="{{ asset('images/icons/reservation-icon.svg') }}" alt="Manajemen Reservasi"
                    class="w-16 h-16 mb-4 opacity-50">
                <span class="text-white text-lg">Manajemen Reschedule</span>
            </a>

            <a href="{{ route('laporan.index') }}"
                class="btn-gradient rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
                <img src="{{ asset('images/icons/reservation-icon.svg') }}" alt="Manajemen Reservasi"
                    class="w-16 h-16 mb-4 opacity-50">
                <span class="text-white text-lg">Manajemen Laporan</span>
            </a>

            <a href="{{ route('admin.manajemen-ruangan.index') }}"
                class="btn-gradient rounded-lg p-6 flex flex-col items-center justify-center hover:bg-green-500 transition">
                <img src="{{ asset('images/icons/reservation-icon.svg') }}" alt="Manajemen Reservasi"
                    class="w-16 h-16 mb-4 opacity-50">
                <span class="text-white text-lg">Manajemen Ruangan</span>
            </a>
            {{-- {{ route('laporan') }} --}}
        </div>
    </div>
@endsection