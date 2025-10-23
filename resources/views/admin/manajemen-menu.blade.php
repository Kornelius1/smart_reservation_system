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
    <div class="p-1 lg:p-8">
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
                
                <div class="flex justify-start items-center my-4 space-x-4"> 
                    <div class="form-control relative my-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 absolute left-3 top-1.5 text-gray-500" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                       <input id="searchInput" type="text" placeholder="Search..."
                            class="input input-sm input-bordered w-72 pl-10" />
                    </div>
                    <button id="tambahMenuBtn" class="btn btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Tambah Menu</button>
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
                    <table id="menuTable" class="table w-full">
                        <thead>
                            <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Kategori</th>
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
                                                    {{-- Ini sudah benar, menggunakan URL langsung --}}
                                                    <img src="{{ $item->image_url }}"
                                                         alt="{{ $item->name }}" class="w-full h-full object-cover" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $item->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    {{-- Tampilkan kategori dengan format yang rapi --}}
                                    <td>{{ ucwords(str_replace('-', ' ', $item->category)) }}</td>
                                    <td><span class="badge badge-sm"></span></td>
                                    <td>
                                        <div class="flex items-center justify-center space-x-2">
                                            
                                            <form action="{{ route('menu.updateStatus', $item->id) }}" method="POST" class="toggle-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="tersedia" value="{{ $item->tersedia ? 'false' : 'true' }}">
                                                <input type="checkbox" class="toggle toggle-md"
                                                       {{ $item->tersedia ? 'checked' : '' }} 
                                                       onchange="this.form.submit()"
                                                />
                                            </form>
                                            
                                            <button
                                                class="btn btn-xs btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none btn-ubah-detail"
                                                data-id="{{ $item->id }}"
                                                data-nama="{{ $item->name }}"
                                                data-harga="{{ $item->price }}"
                                                data-kategori="{{ $item->category }}">Ubah Detail</button>
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

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Kategori</span></label>
                    
                    {{-- ==================================================== --}}
                    {{-- PERUBAHAN DI SINI: Dropdown Kategori Tambah Menu    --}}
                    {{-- ==================================================== --}}
                    <select id="tambah_kategori_menu" name="kategori_menu" class="select select-bordered w-full">
                        <option value="" disabled selected>Pilih Kategori</option>
                        {{-- Loop dari variabel $categories di controller --}}
                        @foreach ($categories as $category)
                            {{-- 
                                value="{{ $category }}" -> isinya "heavy-meal"
                                Tampilannya -> "Heavy Meal"
                            --}}
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
            
            <form id="form_ubah_detail" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Nama Menu</span></label>
                    <input type="text" id="ubah_nama_menu" name="ubah_nama_menu" class="input input-bordered w-full" />
                    @error('ubah_nama_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Harga Menu</span></label>
                    <input type="number" id="ubah_harga_menu" name="ubah_harga_menu" class="input input-bordered w-full" />
                    @error('ubah_harga_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Kategori</span></label>
                    
                    {{-- ==================================================== --}}
                    {{-- PERUBAHAN DI SINI: Dropdown Kategori Ubah Menu     --}}
                    {{-- ==================================================== --}}
                    <select id="ubah_kategori_menu" name="ubah_kategori_menu" class="select select-bordered w-full">
                        <option value="" disabled>Pilih Kategori</option>
                        {{-- Loop dari variabel $categories di controller --}}
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">
                                {{ ucwords(str_replace('-', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('ubah_kategori_menu')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text text-brand-text">Ganti Gambar (Opsional)</span></label>
                    <input type="file" id="ubah_gambar_menu" name="ubah_gambar_menu" 
                           class="file-input file-input-bordered w-full" />
                    <label class="label"><span class="label-text-alt">Kosongkan jika tidak ingin ganti gambar</span></label>
                    @error('ubah_gambar_menu')
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

@endsection

{{-- 
    TAMBAHAN: Script ini akan otomatis membuka kembali modal 
    jika terjadi error validasi dari controller
--}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->has('nama_menu') || $errors->has('harga_menu') || $errors->has('kategori_menu') || $errors->has('gambar_menu'))
            document.getElementById('modal_tambah_menu').showModal();
        @endif
    });
</script>