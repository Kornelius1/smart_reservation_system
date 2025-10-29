@extends('layouts.admin')

@section('title', 'Manajemen Reservasi')


@push('styles')
      {{-- Style untuk toggle tidak lagi diperlukan, tapi saya biarkan jika dipakai di tempat lain --}}
      <style>
        .toggle {
            --toggle-handle-color: white !important;
        }

        .toggle:checked {
            background-image: none !important;
        }
    </style>
@endpush

@section('content')
      <div class="p-4 lg:p-8">
         <div class="flex items-center gap-3 mb-8">
            <button onclick="window.history.back()" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-2xl">Manajemen Reservasi</h1>
        </div>

        {{-- ============================================= --}}
        {{-- BLOK ALERT UNTUK NOTIFIKASI SUKSES/ERROR --}}
        {{-- ============================================= --}}
        @if (session('success'))
            <div role="alert" class="alert alert-success mb-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div role="alert" class="alert alert-error mb-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        {{-- ============================================= --}}

            <div class="card w-full bg-white shadow-xl">
                <div class="card-body">
                    <h1 class="text-2xl font-bold brand-text-1 border-b-4 border-brand-primary pb-2">MANAJEMEN RESERVASI</h1>


                    <div class="form-control relative my-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 absolute left-3 top-1.5 text-gray-500"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                       <input id="searchInput" type="text" placeholder="Cari berdasarkan nama customer..."
                            class="input input-sm input-bordered w-72 pl-10" />
                    </div>


                    <div class="overflow-x-auto mt-4">

                        <table id="tableData" class="table w-full">
                            {{-- HEADER TABEL --}}
                         <thead>
                            <tr class="brand-text-1 text-center" style="background-color: #C6D2B9;">
                                <th>ID Reservasi</th>
                                <th>ID Transaksi</th>
                                <th>Nomor Meja</th>
                                <th>Nomor Ruangan</th>
                                <th>Nama Customer</th>
                                <th>Nomor Telepon</th>
                                <th>Jumlah Orang</th>
                                <th>Daftar Pesanan</th>
                                <th>Tanggal</th>
                                <th>Waktu Reservasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        {{-- ISI TABEL --}}
                        <tbody class="text-brand-text">
                            @forelse ($reservations as $reservation)
                                <tr class="text-center">
                                    <th>{{ $reservation->id_reservasi }}</th>
                                    <td>{{ $reservation->id_transaksi }}</td>
                                    <td>{{ $reservation->nomor_meja ?? '-' }}</td>
                                    <td>{{ $reservation->nomor_ruangan ?? '-' }}</td>
                                    <td class="whitespace-nowrap">{{ $reservation->nama }}</td>
                                    <td>{{ $reservation->nomor_telepon ?? '-' }}</td>
                                    <td>{{ $reservation->jumlah_orang ?? '-' }} Orang</td>
                                    
                                    {{-- Kolom Daftar Pesanan (Tidak diubah) --}}
                                    <td class="text-xs text-left">
                                        @if ($reservation->products->isEmpty())
                                            <span>-</span>
                                        @else
                                            <ul class="list-disc list-inside">
                                                @foreach ($reservation->products as $product)
                                                    <li class="whitespace-nowrap">
                                                        {{ $product->name }} ({{ $product->pivot->quantity }}x)
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    <td>{{ $reservation->tanggal ? $reservation->tanggal->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $reservation->waktu ? \Carbon\Carbon::parse($reservation->waktu)->format('H:i') : '-' }} WIB</td>
                                    
                                    {{-- ============================================= --}}
                                    {{-- PERBAIKAN: BLOK STATUS --}}
                                    {{-- (Case-sensitivity diperbaiki, typo 'classs' diperbaiki, 'pending' ditambahkan) --}}
                                    {{-- ============================================= --}}
                                    <td class="whitespace-nowrap">
                                        @switch($reservation->status)
                                            @case('pending')
                                                <span class="badge badge-warning text-white badge-sm">Pending</span>
                                                @break
                                        
                                            @case('akan datang')
                                                <span class="badge badge-info text-white badge-sm">Akan Datang</span>
                                                @break

                                            @case('check-in')
                                                <span class="badge badge-success text-white badge-sm">Berlangsung</span>
                                                @break

                                            @case('selesai')
                                                <span class="badge badge-ghost badge-sm">Selesai</span> {{-- Typo 'classs' diperbaiki --}}
                                                @break

                                            @case('dibatalkan')
                                                <span class="badge badge-error text-white badge-sm">Dibatalkan</span>
                                                @break
                                            
                                            {{-- (Case 'Tidak Datang' dihapus karena tidak ada di controller/seeder) --}}

                                            @default
                                                <span class="badge">{{ $reservation->status }}</span>
                                        @endswitch
                                    </td>
                                    
                                    {{-- ========================================= --}}
                                    {{-- PERBAIKAN: BLOK AKSI --}}
                                    {{-- (Case-sensitivity diperbaiki, 'pending' ditambahkan) --}}
                                    {{-- ========================================= --}}
                                    <td class="whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            
                                            @if ($reservation->status == 'akan datang')
                                                {{-- Form untuk Check-in --}}
                                                <form action="{{ route('admin.reservasi.checkin', $reservation->id_reservasi) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-xs text-white">Check-in</button>
                                                </form>

                                                {{-- Form untuk Batalkan --}}
                                                <form action="{{ route('admin.reservasi.cancel', $reservation->id_reservasi) }}" method="POST" class="m-0"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin MEMBATALKAN reservasi ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-error btn-xs text-white">Batalkan</button>
                                                </form>
                                            
                                            @elseif ($reservation->status == 'pending')
                                                {{-- Form untuk Batalkan (hanya pending) --}}
                                                <form action="{{ route('admin.reservasi.cancel', $reservation->id_reservasi) }}" method="POST" class="m-0"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin MEMBATALKAN reservasi ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-error btn-xs text-white">Batalkan</button>
                                                </form>

                                            @elseif ($reservation->status == 'check-in')
                                                {{-- Form untuk Selesaikan --}}
                                                <form action="{{ route('admin.reservasi.complete', $reservation->id_reservasi) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary btn-xs text-white">Selesaikan</button>
                                                </form>

                                            @else
                                                {{-- Status Selesai, Dibatalkan --}}
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Jika tidak ada data --}}
                                <tr>
                                    <td colspan="12" class="text-center py-4">Tidak ada data reservasi ditemukan.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
@vite('resources/js/manajemen-reservasi.js')
@endpush