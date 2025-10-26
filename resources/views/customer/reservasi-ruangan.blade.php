@extends('layouts.guest')

@section('title', 'Reservasi Ruangan')

@section('header')
    <header class="bg-dark-green text-white p-4 text-center text-lg font-bold">
        Pilih Ruangan Anda!
    </header>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-5xl">

        @forelse ($rooms as $room)
            <div
                class="bg-amber-50 rounded-3xl p-6 shadow-md text-center transition-transform duration-200 ease-in-out hover:scale-105">

                <img src="{{ asset('storage/' . $room->image_url) }}" alt="{{ $room->nama_ruangan }}"
                    class="rounded-lg mb-4 w-full h-48 object-cover" />

                <h2 class="text-xl font-bold mb-2">{{ $room->nama_ruangan }}</h2>

                <div class="text-sm space-y-1 text-left mx-auto max-w-max mb-4">
                    <p>👥 Kapasitas: {{ $room->kapasitas }} Orang</p>
                    <p>📍 Lokasi: {{ $room->lokasi }}</p>
                    <p>✨ Fasilitas: {{ $room->fasilitas }}</p>
                    <p>💰 Min. Order: Rp{{ number_format($room->minimum_order, 0, ',', '.') }} / 3 jam</p>
                    <p class="text-xs italic">{{ $room->keterangan }}</p>
                </div>



                @if ($room->status == 'tersedia')
                    <a href="/pesanmenu?room_name={{ $room->nama_ruangan }}&min_order={{ $room->minimum_order }}"
                        class="btn-custom inline-block">
                        Pilih Ruangan Ini
                    </a>
                @else

                    <button class="btn-custom inline-block opacity-50 cursor-not-allowed" disabled>
                        Tidak Tersedia
                    </button>
                @endif

            </div>
        @empty
            <div class="col-span-1 md:col-span-2 text-center text-gray-500 py-10">
                <p class="text-lg">Maaf, saat ini tidak ada ruangan yang tersedia.</p>
            </div>
        @endforelse

    </div>
@endsection