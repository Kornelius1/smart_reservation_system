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
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold border-b-4 border-brand-primary pb-2">MANAJEMEN MENU</h1>             
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
                        <button id="tambahMenuBtn" class="btn text-white bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Tambah Menu</button>
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
                    {{-- ... Isi tabel Anda tetap sama ... --}}
                    <table id="menuTable" class="table w-full">
                        {{-- (Konten tabel tidak perlu diubah) --}}
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
                                                    <img src="{{ asset('images/menu/' . $item['foto']) }}"
                                                        alt="{{ $item['nama'] }}" class="w-full h-full object-cover" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $item['nama'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                    <td>{{ $item['kategori'] }}</td>
                                    <td><span class="badge badge-sm"></span></td>
                                    <td>
                                        <div class="flex items-center justify-center space-x-2">
                                            <input type="checkbox" class="toggle toggle-md"
                                                {{ $item['tersedia'] ? 'checked' : '' }} />
                                            <button
                                                class="btn btn-xs text-white bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none btn-ubah-detail"
                                                data-nama="{{ $item['nama'] }}" data-harga="{{ $item['harga'] }}"
                                                data-kategori="{{ $item['kategori'] }}"
                                                data-foto="{{ $item['foto'] }}">Ubah Detail</button>
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

    {{-- Modal diletakkan di sini, masih di dalam @section('content') --}}
    <dialog id="modal_tambah_menu" class="modal">
        {{-- ... isi modal tambah menu Anda ... --}}
    </dialog>

    <dialog id="modal_ubah_detail" class="modal">
        {{-- ... isi modal ubah detail Anda ... --}}
    </dialog>

@endsection