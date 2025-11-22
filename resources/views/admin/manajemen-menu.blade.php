@extends('layouts.admin')

@section('title', 'Manajemen Menu')

@push('styles')
    <style>
        .toggle {
            --toggle-handle-color: white !important;
        }

        .toggle:checked {
            background-image: none !important;
        }

        /* Menyembunyikan panah di input number */
        input[type='number']::-webkit-outer-spin-button,
        input[type='number']::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type='number'] {
            -moz-appearance: textfield;
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
            <h1 class="text-2xl">Manajemen Menu</h1>
        </div>
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold border-b-4 border-brand-primary pb-2">MANAJEMEN MENU</h1> 
                
                {{-- Notifikasi Sukses --}}
                @if (session('success'))
                    <div class="alert alert-success shadow-lg mt-4">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                {{-- Notifikasi Error (jika ada) --}}
                 @if (session('error'))
                    <div class="alert alert-error shadow-lg mt-4">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                
                {{-- Notifikasi Error Validasi (Hanya untuk Modal Ubah) --}}
                @if ($errors->update->any())
                    <div class="alert alert-error shadow-lg mt-4">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Gagal memperbarui menu. Cek kembali isian Anda di modal "Ubah Detail".</span>
                        </div>
                    </div>
                @endif

               
                <div class="flex flex-col sm:flex-row justify-start sm:items-center my-4 gap-4"> 
                    <div class="form-control relative"> {{-- my-2 dihapus krn sdh pakai gap-4 --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 absolute left-3 top-1.5 text-gray-500" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{-- 
                            - w-full: Lebar penuh di HP
                            - sm:w-72: Kembali ke lebar 72 di layar small ke atas
                        --}}
                        <input id="searchInput" type="text" placeholder="Cari berdasarkan nama menu..."
                            class="input input-sm input-bordered w-full sm:w-72 pl-10" />
                    </div>
                    {{-- 
                        - sm:w-auto: Lebar tombol otomatis di layar small ke atas
                                    (Di HP akan otomatis full-width krn parent-nya flex-col)
                    --}}
                    <button id="tambahMenuBtn" class="btn btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none sm:w-auto">Tambah Menu</button>
                </div>
                {{-- PERBAIKAN RESPONSIVE SELESAI --}}
                {{-- ================================================================== --}}

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

                {{-- Div ini sudah benar, jangan diubah. Ini yang menangani tabel --}}
                <div class="overflow-x-auto">
                    <table id="menuTable" class="table w-full">
                        <thead>
                            <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Stok</th> {{-- TAMBAHAN: Kolom Stok --}}
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-brand-text">
                            @foreach ($menuItems as $item)
                                <tr class="text-center">
                                    <th>{{ $loop->iteration }}</th>
                                    <td class="text-left">
                                        <div class="flex items-center space-x-5">
                                            <div class="avatar">
                                                <div class="mask mask-squircle w-12 h-12">
                                                    {{-- PERBAIKAN: Gunakan asset() untuk gambar dari storage --}}
                                                    <img src="{{ Str::startsWith($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}"
                                                        alt="{{ $item->name }}" class="w-full h-full object-cover" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $item->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ ucwords(str_replace('-', ' ', $item->category)) }}</td>
                                    
                                    {{-- TAMBAHAN: Tampilkan Stok --}}
                                    <td>{{ $item->stock }}</td> 
                                    
                                    {{-- PERBAIKAN: Tampilkan Status berdasarkan Stok dan Ketersediaan --}}
                                    <td>
                                        @if ($item->stock == 0)
                                            <span class="badge badge-sm badge-error">Stok Habis</span>
                                        @elseif ($item->tersedia)
                                            <span class="badge badge-sm badge-success">Tersedia</span>
                                        @else
                                            <span class="badge badge-sm badge-error">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div class="flex items-center justify-center space-x-2">
                                            
                                            <form action="{{ route('menu.updateStatus', $item->id) }}" method="POST" class="toggle-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="tersedia" value="{{ $item->tersedia ? 'false' : 'true' }}">
                                                <input type="checkbox" class="toggle toggle-md"
                                                    {{ $item->tersedia ? 'checked' : '' }} 
                                                    onchange="this.form.submit()"
                                                    {{-- LOGIKA STOK: Nonaktifkan toggle jika stok 0 --}}
                                                    {{ $item->stock == 0 ? 'disabled' : '' }} 
                                                />
                                            </form>
                                            
                                            <button
                                                class="btn btn-xs btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none btn-ubah-detail"
                                                data-id="{{ $item->id }}"
                                                data-nama="{{ $item->name }}"
                                                data-harga="{{ $item->price }}"
                                                data-kategori="{{ $item->category }}"
                                                data-stok="{{ $item->stock }}" {{-- TAMBAHAN: Kirim data stok --}}
                                                {{-- PERBAIKAN: URL action untuk update --}}
                                                data-action="{{ route('menu.update', $item->id) }}" 
                                            >Ubah Detail</button>

                                            {{-- TAMBAHAN: Tombol Hapus --}}
                                            <button 
                                                class="btn btn-xs btn-error btn-hapus-menu"
                                                data-id="{{ $item->id }}"
                                                data-nama="{{ $item->name }}"
                                                data-action="{{ route('menu.destroy', $item->id) }}"
                                            >Hapus</button>

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

    {{-- =================================== --}}
    {{-- MODAL TAMBAH MENU (SUDAH DIISI) --}}
    {{-- =================================== --}}
    <dialog id="modal_tambah_menu" class="modal">
        <div class="modal-box bg-white">
            <h3 class="font-bold text-lg text-brand-text mb-4">Tambah Menu Baru</h3>
            
            <form id="form_tambah_menu" method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Nama Menu</span></label>
                    <input type="text" id="tambah_nama_menu" name="nama_menu" placeholder="Contoh: Nasi Goreng" 
                           class="input input-bordered w-full" value="{{ old('nama_menu') }}" />
                    {{-- PERBAIKAN: Error bag default (bukan 'update') --}}
                    @error('nama_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Harga Menu</span></label>
                    <input type="number" id="tambah_harga_menu" name="harga_menu" placeholder="Contoh: 15000" 
                           class="input input-bordered w-full" value="{{ old('harga_menu') }}" />
                    @error('harga_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- TAMBAHAN: Input Stok --}}
                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Stok Awal</span></label>
                    <input type="number" id="tambah_stok_menu" name="stok_menu" placeholder="Contoh: 10" 
                           class="input input-bordered w-full" value="{{ old('stok_menu') }}" />
                    @error('stok_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Kategori</span></label>
                    
                    <select id="tambah_kategori_menu" name="kategori_menu" class="select select-bordered w-full">
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ old('kategori_menu') == $category ? 'selected' : '' }}>
                                {{ ucwords(str_replace('-', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text text-brand-text">Gambar Menu</span></label>
                    <input type="file" id="tambah_gambar_menu" name="gambar_menu" 
                           class="file-input file-input-bordered w-full" />
                    @error('gambar_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="modal_tambah_menu.close()">Batal</button>
                    <button type="submit" class="btn btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- =================================== --}}
    {{-- MODAL UBAH DETAIL (SUDAH DIISI) --}}
    {{-- =================================== --}}
    <dialog id="modal_ubah_detail" class="modal">
        <div class="modal-box bg-white">
            <h3 class="font-bold text-lg text-brand-text mb-4">Ubah Detail Menu</h3>
            
            {{-- PERBAIKAN: Action form akan diisi oleh JavaScript --}}
            <form id="form_ubah_detail" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT') 
                
                {{-- TAMBAHAN: Hidden input untuk ID, PENTING untuk validasi --}}
                <input type="hidden" id="update_menu_id" name="update_menu_id" value="{{ old('update_menu_id') }}">

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Nama Menu</span></label>
                    <input type="text" id="ubah_nama_menu" name="ubah_nama_menu" class="input input-bordered w-full" value="{{ old('ubah_nama_menu') }}" />
                    {{-- PERBAIKAN: Ambil error dari error bag 'update' --}}
                    @error('ubah_nama_menu', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Harga Menu</span></label>
                    <input type="number" id="ubah_harga_menu" name="ubah_harga_menu" class="input input-bordered w-full" value="{{ old('ubah_harga_menu') }}" />
                    @error('ubah_harga_menu', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- TAMBAHAN: Input Stok --}}
                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Stok</span></label>
                    <input type="number" id="ubah_stok_menu" name="ubah_stok_menu" class="input input-bordered w-full" value="{{ old('ubah_stok_menu') }}" />
                    @error('ubah_stok_menu', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Kategori</span></label>
                    
                    <select id="ubah_kategori_menu" name="ubah_kategori_menu" class="select select-bordered w-full">
                        <option value="" disabled>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ old('ubah_kategori_menu') == $category ? 'selected' : '' }}>
                                {{ ucwords(str_replace('-', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('ubah_kategori_menu', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text text-brand-text">Ganti Gambar (Opsional)</span></label>
                    <input type="file" id="ubah_gambar_menu" name="ubah_gambar_menu" 
                           class="file-input file-input-bordered w-full" />
                    <label class="label"><span class="label-text-alt">Kosongkan jika tidak ingin ganti gambar</span></label>
                    @error('ubah_gambar_menu', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="modal_ubah_detail.close()">Batal</button>
                    <button type="submit" class="btn btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Update</button>
                </div>
            </form>
        </div>
    </dialog>


    {{-- =================================== --}}
    {{-- TAMBAHAN: MODAL KONFIRMASI HAPUS  --}}
    {{-- =================================== --}}
    <dialog id="modal_hapus_menu" class="modal">
        <div class="modal-box bg-white">
            <h3 class="font-bold text-lg text-brand-text">Konfirmasi Hapus</h3>
            <p class="py-4 text-brand-text">Apakah Anda yakin ingin menghapus menu "<span id="hapus_nama_menu" class="font-bold"></span>"?</p>
            
            <form id="form_hapus_menu" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-action">
                    <button type="button" class="btn" onclick="modal_hapus_menu.close()">Batal</button>
                    <button type="submit" class="btn btn-error">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </dialog>

@endsection

{{-- ========================================================= --}}
{{-- PERBAIKAN: Pindahkan script ke @push('scripts') 
     agar di-load setelah layout
--}}
{{-- ========================================================= --}}
@push('scripts')
@vite('resources/js/manajemen-menu.js')
<script>
        document.addEventListener('DOMContentLoaded', function () {
        
        // --- MODAL TAMBAH ---
        const modalTambah = document.getElementById('modal_tambah_menu');
        document.getElementById('tambahMenuBtn').addEventListener('click', () => {
            modalTambah.showModal();
        });

        // --- MODAL UBAH DETAIL ---
        const modalUbah = document.getElementById('modal_ubah_detail');
        const formUbah = document.getElementById('form_ubah_detail');
        const ubahIdInput = document.getElementById('update_menu_id');
        const ubahNamaInput = document.getElementById('ubah_nama_menu');
        const ubahHargaInput = document.getElementById('ubah_harga_menu');
        const ubahStokInput = document.getElementById('ubah_stok_menu');
        const ubahKategoriSelect = document.getElementById('ubah_kategori_menu');

        document.querySelectorAll('.btn-ubah-detail').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nama = button.dataset.nama;
                const harga = button.dataset.harga;
                const stok = button.dataset.stok;
                const kategori = button.dataset.kategori;
                const action = button.dataset.action;

                // Isi form
                formUbah.action = action; // Set action form
                ubahIdInput.value = id;
                ubahNamaInput.value = nama;
                ubahHargaInput.value = harga;
                ubahStokInput.value = stok;
                ubahKategoriSelect.value = kategori;
                
                modalUbah.showModal();
            });
        });

        // --- MODAL HAPUS ---
        const modalHapus = document.getElementById('modal_hapus_menu');
        const formHapus = document.getElementById('form_hapus_menu');
        const hapusNamaSpan = document.getElementById('hapus_nama_menu');

        document.querySelectorAll('.btn-hapus-menu').forEach(button => {
            button.addEventListener('click', () => {
                const nama = button.dataset.nama;
                const action = button.dataset.action;

                formHapus.action = action; // Set action form
                hapusNamaSpan.textContent = nama; // Set nama di teks konfirmasi

                modalHapus.showModal();
            });
        });


        // ==============================================================
        // PERBAIKAN: Script untuk membuka kembali modal jika ada error
        // ==============================================================

        // 1. Cek jika ada error validasi 'tambah' (default error bag)
        @if($errors->any() && !$errors->hasBag('update'))
            modalTambah.showModal();
        @endif

        // 2. Cek jika ada error validasi 'ubah' (error bag 'update')
        // Kita juga butuh ID menu yang error dari session
        @if($errors->update->any() && session('update_error_id'))
            // Ambil ID yang error dari session
            const errorId = {{ session('update_error_id') }}; 
            
            // Temukan tombol 'Ubah Detail' yang sesuai dengan ID itu
            const errorButton = document.querySelector(`.btn-ubah-detail[data-id="${errorId}"]`);

            if (errorButton) {
                // Isi form dengan data LAMA (dari old())
                formUbah.action = errorButton.dataset.action;
                ubahIdInput.value = errorId;
                ubahNamaInput.value = "{{ old('ubah_nama_menu') }}";
                ubahHargaInput.value = "{{ old('ubah_harga_menu') }}";
                ubahStokInput.value = "{{ old('ubah_stok_menu') }}";
                ubahKategoriSelect.value = "{{ old('ubah_kategori_menu') }}";

                // Tampilkan modal
                modalUbah.showModal();
            }
        @endif

    });
</script>
@endpush