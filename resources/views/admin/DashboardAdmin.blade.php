@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gray-50 p-4 sm:p-6">
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Dashboard Admin</h1>
        </div>

        <!-- === GRID BENTO UTAMA === -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 max-w-7xl mx-auto mb-6">
            <!-- (Semua card utama tetap sama seperti sebelumnya) -->
            <!-- 1. Manajemen Menu -->
            <a href="{{ route('menu.index') }}"
                class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1 hover:scale-[1.01]">
                <div class="bg-blue-500 w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/manaj-menu.png') }}" alt="Manajemen Menu"
                        class="w-7 h-7 opacity-90 filter brightness-200">
                </div>
                <h3 class="text-gray-800 font-bold text-lg">Manajemen Menu</h3>
                <p class="text-gray-500 text-sm mt-1">Kelola hidangan, kategori, dan harga</p>
            </a>

            <!-- 2. Reservasi -->
            <a href="{{ route('admin.reservasi.index') }}"
                class="lg:row-span-2 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-6 flex flex-col justify-end text-white shadow-lg relative overflow-hidden transition-all duration-300 ease-out hover:shadow-2xl hover:-translate-y-1.5 before:absolute before:inset-0 before:bg-white before:opacity-0 before:scale-150 before:transition-opacity before:duration-500 hover:before:opacity-10">
                <div class="bg-white/20 w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/manaj-reservasi.png') }}" alt="Manajemen Reservasi" class="w-7 h-7">
                </div>
                <h3 class="font-bold text-lg">Reservasi</h3>
                <p class="text-amber-100 text-sm mt-1">Kelola pemesanan tamu</p>
            </a>

            <!-- 3. Manajemen Meja -->
            <a href="{{ route('manajemen-meja') }}"
                class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-out hover:shadow-lg hover:-translate-y-1 hover:rotate-[0.5deg]">
                <div class="bg-emerald-500 w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/manaj-meja.png') }}" alt="Manajemen Meja"
                        class="w-7 h-7 opacity-90 filter brightness-200">
                </div>
                <h3 class="text-gray-800 font-bold">Manajemen Meja</h3>
            </a>

            <!-- 4. Reschedule -->
            <a href="{{ route('manajemen-reschedule') }}"
                class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-out hover:shadow-lg hover:-translate-y-1 hover:border-rose-300">
                <div
                    class="bg-rose-500 w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-colors duration-300 hover:bg-rose-600">
                    <img src="{{ asset('images/icons/manaj-reschedule.png') }}" alt="Manajemen Reschedule"
                        class="w-7 h-7 opacity-90 filter brightness-200">
                </div>
                <h3 class="text-gray-800 font-bold">Reschedule</h3>
            </a>

            <!-- 5. Laporan -->
            <a href="{{ route('laporan.index') }}"
                class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-out hover:shadow-lg hover:scale-[1.02] hover:-translate-y-0.5">
                <div class="bg-indigo-500 w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/manaj-laporan.png') }}" alt="Manajemen Laporan"
                        class="w-7 h-7 opacity-90 filter brightness-200">
                </div>
                <h3 class="text-gray-800 font-bold">Laporan</h3>
            </a>

            <!-- 6. Ruangan -->
            <a href="{{ route('admin.manajemen-ruangan.index') }}"
                class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 ease-out hover:shadow-lg hover:-translate-y-1 hover:border-teal-300 hover:ring-2 hover:ring-teal-100">
                <div class="bg-teal-500 w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/manaj-ruangan.png') }}" alt="Manajemen Ruangan"
                        class="w-7 h-7 opacity-90 filter brightness-200">
                </div>
                <h3 class="text-gray-800 font-bold">Manajemen Ruangan</h3>
            </a>
        </div>

        <!-- === BENTO FOOTER SECTION (DINAMIS - BULANAN + MENDATANG) === -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 max-w-7xl mx-auto">
            <!-- Statistik Bulan Ini -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm self-start order-last lg:order-first">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Bulan Ini
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Reservasi Aktif</span>
                        <span class="font-semibold text-blue-600">{{ $totalReservasi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Tamu</span>
                        <span class="font-semibold text-emerald-600">{{ $totalTamu }} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimasi Pendapatan</span>
                        <span class="font-semibold text-amber-600">Rp
                            {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4 text-center">
                    Data per {{ now()->format('d F Y') }}
                </p>
            </div>
            <!-- Jadwal Mendatang (Hari ini + 2 hari) -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm self-start order-last lg:order-first">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Jadwal Mendatang (Hari ini + 2 hari)
                </h3>
                <ul class="space-y-3 text-sm">
                    @forelse($reservasiMendatang as $res)
                        <li class="flex items-start pb-2 border-b border-gray-100 last:border-0 last:pb-0">
                            <div class="mr-3 mt-0.5">
                                <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $res->nama }}</p>
                                <p class="text-gray-600 text-xs">
                                    {{ \Carbon\Carbon::parse($res->tanggal)->translatedFormat('l, d F') }} • {{ $res->waktu }}
                                    @if($res->nomor_meja)
                                        • Meja {{ $res->nomor_meja }}
                                    @elseif($res->nomor_ruangan)
                                        • Ruang {{ $res->nomor_ruangan }}
                                    @endif
                                </p>
                                <div class="mt-1 flex items-center text-xs">
                                    <span class="text-gray-500">Tamu:</span>
                                    <span class="ml-1 font-medium">{{ $res->jumlah_orang }} orang</span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">Tidak ada reservasi untuk 3 hari ke depan</li>
                    @endforelse
                    @if($extraCount > 0)
                        <li class="mt-2 flex justify-center">
                            <button onclick="document.getElementById('extra-reservasi').classList.toggle('hidden')"
                                class="bg-amber-100 text-amber-700 px-4 py-2 rounded-xl font-semibold hover:bg-amber-200 transition-colors">
                                ... {{ $extraCount }} reservasi lagi
                            </button>
                        </li>

                        <ul id="extra-reservasi" class="hidden space-y-3 mt-2">
                            @foreach($extraReservasi as $res)
                                <li class="flex items-start pb-2 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="mr-3 mt-0.5">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-800 truncate">{{ $res->nama }}</p>
                                        <p class="text-gray-600 text-xs">
                                            {{ \Carbon\Carbon::parse($res->tanggal)->translatedFormat('l, d F') }} •
                                            {{ $res->waktu }}
                                            @if($res->nomor_meja)
                                                • Meja {{ $res->nomor_meja }}
                                            @elseif($res->nomor_ruangan)
                                                • Ruang {{ $res->nomor_ruangan }}
                                            @endif
                                        </p>
                                        <div class="mt-1 flex items-center text-xs">
                                            <span class="text-gray-500">Tamu:</span>
                                            <span class="ml-1 font-medium">{{ $res->jumlah_orang }} orang</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </ul>
            </div>


        </div>

    </div>
@endsection