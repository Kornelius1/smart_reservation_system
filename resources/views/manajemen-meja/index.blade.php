@extends('layouts.manajemen-meja-layout')

@section('title', 'Manajemen Meja')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Search dan Tombol Tambah -->
    <div class="mb-6 flex justify-between items-center">
        <!-- Search Box -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                   id="search-meja" 
                   placeholder="Cari Meja" 
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 w-72">
        </div>

     
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <!-- Header dengan warna hijau sesuai gambar -->
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nomor Meja</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kapasitas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Lokasi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Toggle</th>
                </tr>
            </thead>
            <tbody id="table-body" class="bg-white divide-y divide-gray-200">
                @include('manajemen-meja.partials.table-rows', ['meja' => $meja])
            </tbody>
        </table>
    </div>
</div>



@endsection