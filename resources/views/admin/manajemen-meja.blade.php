@extends('layouts.admin')

@section('title', 'Manajemen Meja')

@push('styles')
    <style>
        .toggle {
            --toggle-handle-color: white !important;
        }

        .toggle:checked {
            background-image: none !important;
        }

        /* Atur warna badge agar konsisten dengan status */
        .badge-success {
            background-color: #36D399; /* Warna hijau DaisyUI */
            color: white;
            border: none;
        }

        .badge-error {
            background-color: #F87272; /* Warna merah DaisyUI */
            color: white;
            border: none;
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
            <h1 class="text-2xl">Manajemen Meja</h1>
        </div>
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold border-b-4 border-brand-primary pb-2">MANAJEMEN MEJA</h1>

                <div class="form-control relative my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1.5 text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    <input id="searchInput" type="text" placeholder="Cari berdasarkan lokasi..."
                        class="input input-sm input-bordered w-72 pl-10" />
                </div>

                <div class="flex items-center space-x-2 text-sm mb-4">
                    <span class="text-gray-600">Show</span>
                    <select id="entries" name="entries" class="select select-sm select-ghost w-20">
                        <option selected>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span class="text-gray-600">entries</span>
                </div>

                <div class="overflow-x-auto">
                    <table id="tableData" class="table w-full">
                        {{-- HEADER TABEL --}}
                        <thead>
                            <tr class="text-brand-text text-center" style="background-color: #C6D3B9;">
                                <th>Nomor Meja</th>
                                <th>Kapasitas</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        {{-- ISI TABEL --}}
                        <tbody class="text-brand-text">
                            {{-- Perulangan data dari controller --}}
                            @forelse ($tables as $table)
                                <tr class="text-center">
                                    <th>{{ $table->nomor_meja }}</th>
                                    {{-- Gunakan accessor 'KapasitasFormat' dari model --}}
                                    <td>{{ $table->kapasitasFormat }}</td> 
                                    <td>{{ $table->lokasi }}</td>
                                    <td class="font-medium">
                                        {{-- Tampilkan status dari accessor 'Status' dan beri warna --}}
                                        <span
                                            class="badge badge-sm {{ $table->status_aktif ? 'badge-success' : 'badge-error' }}">
                                            {{ $table->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center space-x-2">
                                            {{-- 
                                              Perbaikan:
                                              1. Cek 'status_aktif' (boolean) dari model
                                              2. Tambahkan class 'toggle-status' untuk selector JS
                                              3. Tambahkan 'data-id' untuk identifikasi meja
                                            --}}
                                            <input type="checkbox"
                                                class="toggle toggle-md toggle-status"
                                                data-id="{{ $table->id }}"
                                                {{ $table->status_aktif ? 'checked' : '' }} />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data meja.</td>
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
{{-- 
  PENTING: Pastikan layout 'layouts.admin' Anda memiliki:
  1. <meta name="csrf-token" content="{{ csrf_token() }}"> di <head>
  2. @stack('scripts') sebelum tag penutup </body>
--}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil semua elemen toggle dengan class 'toggle-status'
        const toggles = document.querySelectorAll('.toggle-status');

        // Tambahkan event listener untuk setiap toggle
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const tableId = this.dataset.id; // Ambil ID meja dari atribut data-id
                const newStatus = this.checked;  // Cek status baru (true/false)
                
                // Ambil URL rute yang sudah kita buat di web.php
                const url = `{{ url('/admin/meja') }}/${tableId}/toggle-status`;

                // Ambil elemen badge di baris yang sama untuk di-update
                const row = this.closest('tr');
                const statusBadge = row.querySelector('.badge');

                // Dapatkan token CSRF dari tag meta
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Kirim permintaan AJAX/Fetch ke server
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Sertakan token CSRF
                        'Accept': 'application/json'
                    },
                    // Kita tidak perlu mengirim body, karena controller hanya membalik status
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal memperbarui status');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Jika sukses, update teks dan warna badge
                        statusBadge.textContent = data.statusText; // 'Available' or 'Not Available'
                        
                        if (data.newStatus) { // newStatus adalah true
                            statusBadge.classList.add('badge-success');
                            statusBadge.classList.remove('badge-error');
                        } else { // newStatus adalah false
                            statusBadge.classList.add('badge-error');
                            statusBadge.classList.remove('badge-success');
                        }
                    } else {
                        // Jika server merespon gagal, kembalikan toggle ke posisi semula
                        this.checked = !newStatus;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Jika ada error, kembalikan toggle ke posisi semula
                    this.checked = !newStatus;
                });
            });
        });

        // (Fungsi search dan pagination Anda bisa ditambahkan di sini)
    });
</script>
@endpush
