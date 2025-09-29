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

        <!-- Tombol Tambah -->
        <button id="btn-tambah-meja" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Meja
        </button>
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

<!-- Modal Form Meja -->
<div id="modal-form-meja" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Tambah Meja</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('modal-form-meja')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="form-meja">
            <input type="hidden" id="meja-id" name="id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Meja <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="nomor-meja" 
                       name="nomor_meja" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                       placeholder="Masukkan nomor meja"
                       required>
                <span class="text-red-500 text-sm hidden" id="error-nomor-meja"></span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kapasitas <span class="text-red-500">*</span>
                </label>
                <select id="kapasitas" name="kapasitas" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                    <option value="">Pilih kapasitas</option>
                    <option value="2">2 Orang</option>
                    <option value="4">4 Orang</option>
                    <option value="6">6 Orang</option>
                    <option value="8">8 Orang</option>
                </select>
                <span class="text-red-500 text-sm hidden" id="error-kapasitas"></span>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Lokasi <span class="text-red-500">*</span>
                </label>
                <select id="lokasi" name="lokasi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                    <option value="">Pilih lokasi</option>
                    <option value="Indoor 1">Indoor 1</option>
                    <option value="Indoor 2">Indoor 2</option>
                    <option value="Outdoor 1">Outdoor 1</option>
                    <option value="Outdoor 2">Outdoor 2</option>
                </select>
                <span class="text-red-500 text-sm hidden" id="error-lokasi"></span>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors" onclick="closeModal('modal-form-meja')">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors flex items-center gap-2" id="btn-submit">
                    <span class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-white" id="btn-loading"></span>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modal-konfirmasi-hapus" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-red-600 mb-4">Konfirmasi Hapus</h3>
        <p class="mb-6 text-gray-600">Apakah Anda yakin ingin menghapus meja ini? Tindakan ini tidak dapat dibatalkan.</p>
        
        <div class="flex justify-end gap-3">
            <button type="button" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors" onclick="closeModal('modal-konfirmasi-hapus')">
                Batal
            </button>
            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors flex items-center gap-2" id="btn-konfirmasi-hapus">
                <span class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-white" id="hapus-loading"></span>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>
@endsection