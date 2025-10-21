@extends('layouts.app')

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
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold text-brand-text border-b-4 border-brand-primary pb-2">
                    MANAJEMEN RESCHEDULE
                </h1>

                <div class="flex justify-start items-center my-4 space-x-4">
                    <div class="form-control relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input id="searchInput" type="text" placeholder="Cari berdasarkan nama customer..."
                            class="input input-sm input-bordered w-72 pl-10" />
                    </div>
                </div>

                <div class="overflow-x-auto mt-4">
                    <table id="tableData" class="table w-full">
                        <thead>
                            <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                <th>ID Reschedule</th>
                                <th>ID Transaksi</th>
                                <th>Nama Customer</th>
                                <th>Tanggal Reschedule</th>
                                <th>Waktu Reschedule</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-brand-text">
                            @foreach ($reschedules as $reschedule)
                                <tr class="text-center">
                                    <td>{{ $reschedule->id_reschedule }}</td>
                                    <td>{{ $reschedule->id_transaksi }}</td>
                                    <td>{{ $reschedule->nama_customer }}</td>
                                    <td>{{ $reschedule->tanggal_reschedule }}</td>
                                    <td>{{ $reschedule->waktu_reschedule }}</td>
                                    <td>
                                        <span
                                            class="badge badge-sm {{ $reschedule->status == 'Pending' ? 'badge-warning' : ($reschedule->status == 'Disetujui' ? 'badge-success' : 'badge-error') }}">
                                            {{ $reschedule->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center">
                                            <button class="btn btn-xs btn-primary" onclick="changeStatus(this)">Ubah
                                                Status</button>
                                            <select class="select select-sm border rounded" onchange="changeAvailable(this)">
                                                <option value="Available" {{ $reschedule->available ? 'selected' : '' }}>
                                                    Available</option>
                                                <option value="NonAvailable" {{ !$reschedule->available ? 'selected' : '' }}>
                                                    Enggak</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

{{-- Pindahkan semua JavaScript ke dalam @push('scripts') --}}
@push('scripts')
    <script>
        function changeStatus(btn) {
            const statusCell = btn.closest('tr').querySelector('td:nth-child(6) span');
            const currentStatus = statusCell.textContent.trim(); // Tambahkan .trim() untuk keamanan
            let newStatus = '';

            if (currentStatus === 'Pending') newStatus = 'Disetujui';
            else if (currentStatus === 'Disetujui') newStatus = 'Ditolak';
            else newStatus = 'Pending';

            statusCell.textContent = newStatus;
            statusCell.className = 'badge badge-sm ' + (newStatus === 'Pending' ? 'badge-warning' : newStatus === 'Disetujui' ? 'badge-success' : 'badge-error');
        }

        function changeAvailable(select) {
            const value = select.value;
            select.className = 'select select-sm border rounded ' + (value === 'Available' ? 'select-success' : 'select-error');
        }

        // Praktik terbaik: Jalankan script setelah DOM selesai dimuat
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const filter = searchInput.value.toLowerCase();
                    const rows = document.querySelectorAll('#tableData tbody tr');
                    rows.forEach(row => {
                        // Periksa apakah teks baris mengandung filter
                        const rowText = row.textContent || row.innerText;
                        row.style.display = rowText.toLowerCase().includes(filter) ? '' : 'none';
                    });
                });
            }
        });
    </script>
@endpush