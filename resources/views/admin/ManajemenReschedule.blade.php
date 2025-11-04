@extends('layouts.admin')

@section('title', 'Manajemen Reschedule')

@push('styles')
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
            <h1 class="text-2xl">Manajemen Reschedule</h1>
        </div>
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                {{-- Judul diubah agar lebih sesuai --}}
                <h1 class="text-2xl font-bold border-b-4 border-brand-primary pb-2">
                    MANAJEMEN RESCHEDULE
                </h1>

                <div class="form-control relative my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1.5 text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                     <input id="searchInput" type="text" placeholder="Cari berdasarkan nama customer..."
                            class="input input-sm input-bordered w-full sm:w-72 pl-10" />
                </div>



                <div class="overflow-x-auto mt-4">
                    <table id="tableData" class="table w-full">
                        <thead>
                            {{-- ==== PERUBAHAN DI SINI: Sesuaikan Kolom Header ==== --}}
                            <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                <th>ID Transaksi</th>
                                <th>Nama Customer</th>
                                <th>Tanggal Reservasi</th>
                                <th>Waktu Reservasi</th>
                                <th>Terakhir Diubah</th> {{-- Kolom baru yang berguna --}}
                            </tr>
                        </thead>
                        <tbody class="text-brand-text">
                            {{-- ==== PERUBAHAN DI SINI: Sesuaikan Loop dan Data ==== --}}
                            {{-- Pastikan controller mengirim 'reservations' --}}
                            @forelse ($reservations as $reservation)
                                <tr class="text-center">
                                    <td>{{ $reservation->id_transaksi }}</td>
                                    <td>{{ $reservation->nama }}</td>
                                    {{-- Format tanggal agar rapi (karena ini objek Carbon) --}}
                                    <td>{{ $reservation->tanggal->format('d M Y') }}</td>
                                    {{-- Format waktu agar rapi (karena ini objek Carbon) --}}
                                    <td>{{ $reservation->waktu ? \Carbon\Carbon::parse($reservation->waktu)->format('H:i') : '-' }}
                                        WIB</td>
                                    {{-- Tampilkan kapan data ini terakhir di-update --}}
                                    <td>{{ $reservation->updated_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Tidak ada data reservasi.</td>
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
@vite('resources/js/manajemen-reschedule.js')
@endpush