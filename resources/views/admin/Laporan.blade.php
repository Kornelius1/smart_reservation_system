{{-- resources/views/laporan/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <button onclick="window.history.back()" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="text-4xl font-bold text-emerald-600">LAPORAN</h1>
    </div>

    {{-- Sidebar Icons (optional) --}}
    <div class="flex gap-4 mb-6">
        <button class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </button>
        <button class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </button>
        <button class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </button>
    </div>

    {{-- Filter Section --}}
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <h2 class="text-xl font-semibold text-gray-600 mb-4">Filter Tanggal</h2>
            
            <form action="{{ route('laporan.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Start Date --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-gray-600">Start Date</span>
                        </label>
                        <input type="date" 
                               name="start_date" 
                               value="{{ request('start_date') }}"
                               class="input input-bordered w-full" 
                               placeholder="Choose Date">
                    </div>

                    {{-- End Date --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-gray-600">End Date</span>
                        </label>
                        <input type="date" 
                               name="end_date" 
                               value="{{ request('end_date') }}"
                               class="input input-bordered w-full" 
                               placeholder="Choose Date">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline btn-sm">Pilih Hari</button>
                    <button type="button" class="btn btn-outline btn-sm">Pilih Minggu</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari Laporan
                    </button>
                    <button type="button" class="btn btn-success btn-sm">Unduh Laporan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card bg-base-100 shadow-sm overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead class="bg-emerald-600 text-white">
                <tr>
                    <th class="text-center">ID Transaksi</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Waktu</th>
                    <th class="text-center">Nama Customer</th>
                    <th class="text-center">Nomor Telepon</th>
                    <th class="text-center">Jumlah Orang</th>
                    <th class="text-center">Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $item)
                <tr>
                    <td class="text-center">{{ $item->id_transaksi }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $item->waktu }} WIB</td>
                    <td class="text-center">{{ $item->nama_customer }}</td>
                    <td class="text-center">{{ $item->nomor_telepon }}</td>
                    <td class="text-center">{{ $item->jumlah_orang }} Orang</td>
                    <td class="text-center">Rp. {{ number_format($item->total_pembayaran, 2, '.', ',') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">
                        Tidak ada data transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($transaksi->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $transaksi->links() }}
    </div>
    @endif
</div>
@endsection