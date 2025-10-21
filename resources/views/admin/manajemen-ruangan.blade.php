@extends('layouts.admin')

@section('title', 'Manajemen Ruangan')

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
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold border-b-4 border-brand-primary pb-2">MANAJEMEN RUANGAN
                </h1>

                <div class="form-control relative my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1.5 text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input id="searchInput" type="text" placeholder="Cari berdasarkan nama ruangan..."
                        class="input input-sm input-bordered w-72 pl-10" />
                </div>


                <div class="overflow-x-auto mt-4">
                    <table id="tableData" class="table w-full">
                        <thead>
                            <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                {{-- PERBAIKAN: Header disesuaikan dengan data --}}
                                <th>ID</th>
                                <th>Nama Ruangan</th>
                                <th>Minimum Order</th>
                            </tr>
                        </thead>
                        <tbody class="text-brand-text">

                            @forelse ($rooms as $room)
                                <tr class="text-center">

                                    <td>{{ $room->id }}</td>
                                    <td>{{ $room->name }}</td>
                                    <td>Rp {{ number_format($room->minimum_order, 0, ',', '.') }}</td>



                                    {{-- PERBAIKAN: Kolom 'Aksi' yang benar --}}
                                    {{-- <td>
                                        <div class="flex items-center justify-center">

                                            <a href="{{ route('manajemen-ruangan.edit', $room->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>

                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    {{-- PERBAIKAN: Colspan disesuaikan menjadi 5 kolom --}}
                                    <td colspan="5" class="text-center py-4">Tidak ada data ruangan</td>
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
    {{-- PERBAIKAN: Tambahkan skrip untuk fungsionalitas pencarian --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const filter = searchInput.value.toLowerCase();
                    const rows = document.querySelectorAll('#tableData tbody tr');
                    rows.forEach(row => {
                        // Cek teks pada kolom kedua (Nama Ruangan)
                        const roomNameCell = row.cells[1];
                        if (roomNameCell) {
                            const rowText = roomNameCell.textContent || roomNameCell.innerText;
                            row.style.display = rowText.toLowerCase().includes(filter) ? '' : 'none';
                        }
                    });
                });
            }
        });
    </script>
@endpush