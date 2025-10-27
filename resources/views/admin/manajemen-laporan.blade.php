@extends('layouts.admin')

@section('title', 'Laporan Reservasi')

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <button onclick="window.history.back()" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-2xl">Manajemen Laporan</h1>
        </div>

        {{-- Filter Section --}}
        <div class="card bg-base-100 shadow-sm mb-6">
            <div class="card-body">
                <h2 class="text-xl font-semibold text-gray-600 mb-4">Filter Laporan</h2>

                {{-- PERBAIKAN: Pastikan route ini ada di web.php --}}
                <form id="filterForm" action="{{ route('laporan.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Start Date --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-gray-600">Dari Tanggal</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="input input-bordered w-full" placeholder="Choose Date">
                        </div>

                        {{-- End Date --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-gray-600">Sampai Tanggal</span>
                            </label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="input input-bordered w-full" placeholder="Choose Date">
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap gap-2 items-center">
                        {{-- OPTIMALISASI: Tombol ini sekarang berfungsi dengan JavaScript --}}
                        <button type="button" id="filterToday" class="btn btn-outline btn-sm">Hari Ini</button>
                        <button type="button" id="filterThisWeek" class="btn btn-outline btn-sm">Minggu Ini</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari Laporan
                        </button>

                        {{-- PERBAIKAN: Tombol unduh diubah menjadi link yang membawa parameter filter --}}
                        <a href="{{ route('laporan.export', request()->query()) }}" class="btn btn-success btn-sm">
                            Unduh Laporan (CSV)
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="card bg-base-100 shadow-sm overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-emerald-600 text-white">
                    <tr>
                        {{-- PERBAIKAN: Kolom disesuaikan dengan tabel 'reservations' --}}
                        <th class="text-center">ID Transaksi</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Waktu</th>
                        <th class="text-center">Nama Customer</th>
                        <th class="text-center">Dibuat Pada</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- PERBAIKAN: Variabel diubah menjadi $reservations --}}
                    @forelse($reservations as $item)
                        <tr>
                            {{-- PERBAIKAN: Kolom disesuaikan dengan database --}}
                            <td class="text-center">{{ $item->id_transaksi }}</td>
                            <td class="text-center">{{ $item->tanggal->format('d/m/Y') }}</td>
                            <td class="text-center">
                                {{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') : '-' }} WIB
                            </td>
                            <td class="text-center">{{ $item->nama }}</td>
                            <td class="text-center">{{ $item->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            {{-- PERBAIKAN: Colspan disesuaikan --}}
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                Tidak ada data reservasi yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        {{-- PERBAIKAN: Variabel diubah menjadi $reservations --}}
        @if ($reservations->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    {{-- OPTIMALISASI: Script untuk tombol filter cepat --}}
  @vite('resources/js/manajemen-laporan.js')
@endpush