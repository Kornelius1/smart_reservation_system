@extends('layouts.app')

@section('title', 'Reschedule Reservasi')

@section('content')

    <div class="max-w-2xl mx-auto py-10 px-4 flex flex-col min-h-screen">
        <h1 class="text-3xl font-bold text-center mb-8">Reschedule Reservasi Anda</h1>

        {{-- Pesan Sukses Global --}}
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif

        {{-- Form Pencarian --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Cari Reservasi</h2>
                <p>Masukkan ID Transaksi untuk menemukan detail reservasi Anda.</p>
                <form action="{{ route('reschedule.find') }}" method="GET" class="mt-4">
                    {{-- @csrf tidak wajib untuk method GET --}}
                    <div class="form-control">
                        <div class="join w-full">
                            <input type="text" name="id_transaksi" placeholder="Contoh: TRS001"
                                class="input input-bordered join-item w-full"
                                value="{{ old('id_transaksi', $reservasi['id_transaksi'] ?? '') }}" required />
                            <button type="submit" class="btn btn-gradient join-item">Cari</button>
                        </div>
                    </div>
                </form>

                {{-- Pesan Error Pencarian --}}
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif
            </div>
        </div>

        {{-- Hasil Pencarian (Memuat dari Partial View) --}}
        @if(isset($reservasi))
            @include('reschedule._reservation-details', ['reservasi' => $reservasi, 'bisa_reschedule' => $bisa_reschedule])
        @endif
    </div>
@endsection

@push('scripts')
    {{-- Script Anda sudah bagus dan ditempatkan dengan benar, tidak perlu diubah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timeInput = document.querySelector('input[name="waktu_baru"]');
            if (timeInput) {
                timeInput.addEventListener('invalid', function (event) {
                    if (event.target.validity.rangeOverflow) {
                        event.target.setCustomValidity('Melewati jam operasional. Harap pilih waktu sebelum 23:00.');
                    }
                });
                timeInput.addEventListener('input', function (event) {
                    event.target.setCustomValidity('');
                });
            }
        });
    </script>
@endpush