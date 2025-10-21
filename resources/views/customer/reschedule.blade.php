@extends('layouts.app')

@section('title', 'Reschedule Reservasi')

@section('content')

    <div class="max-w-2xl mx-auto py-10 px-4 flex flex-col min-h-screen">
        <h1 class="text-3xl font-bold text-center mb-8">Reschedule Reservasi Anda</h1>

        {{-- Pesan Sukses Global (setelah update berhasil) --}}
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif

        {{-- Form Pencarian --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Cari Reservasi</h2>
                <p>Masukkan ID Transaksi untuk menemukan detail reservasi Anda.</p>
                <form action="{{ route('reschedule.find') }}" method="GET" class="mt-4">
                    <div class="form-control">
                        <div class="join w-full">
                            <input type="text" name="id_transaksi" placeholder="Contoh: TRS001"
                                class="input input-bordered join-item w-full"
                                value="{{ old('id_transaksi', $reservasi->id_transaksi ?? request('id_transaksi')) }}" required />
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

{{-- Script validasi jam (Sudah benar) --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // (Script Anda untuk validasi jam 23:00)
        });
    </script>
@endpush