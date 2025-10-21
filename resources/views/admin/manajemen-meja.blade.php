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
    </style>
@endpush

@section('content')
    <div class="p-4 lg:p-8">
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold border-b-4 border-brand-primary pb-2">MANAJEMEN MEJA</h1>

               <div class="form-control relative my-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 absolute left-3 top-1.5 text-gray-500" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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
                            <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                <th>Nomor Meja</th>
                                <th>Kapasitas</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        {{-- ISI TABEL --}}
                        <tbody class="text-brand-text">
                            @foreach ($tables as $table)
                                <tr class="text-center">
                                    <th>{{ $table['nomor_meja'] }}</th>
                                    <td>{{ $table['kapasitas'] }} Orang</td>
                                    <td>{{ $table['lokasi'] }}</td>
                                    <td>
                                        <span class="badge badge-sm" style="border: none;"></span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center space-x-2">
                                            {{-- Toggle DIBESARKAN (md) dan dicek berdasarkan 'tersedia' --}}
                                            <input type="checkbox" class="toggle toggle-md"
                                                {{ $table['tersedia'] ? 'checked' : '' }} />
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