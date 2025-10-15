@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto py-10 px-4">

    <h1 class="text-3xl font-bold text-center mb-8">Reschedule Reservasi Anda</h1>

    {{-- Pesan Sukses --}}
    @if(session('success'))
        <div role="alert" class="alert text-white mb-6" style="background-color: #414939;">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form Pencarian --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Cari Reservasi</h2>
            <p>Masukkan ID Transaksi untuk menemukan detail reservasi Anda.</p>

            <form action="{{ route('reschedule.find') }}" method="GET" class="mt-4">
                @csrf
                <div class="form-control">
                    <div class="join w-full">
                        <input type="text" name="id_transaksi" placeholder="Contoh: TRS001" class="input input-bordered join-item w-full" value="{{ old('id_transaksi', $reservasi['id_transaksi'] ?? '') }}" required />
                        <button type="submit" class="btn btn-gradient join-item">Cari</button>
                    </div>
                </div>
            </form>

            {{-- Pesan Error Pencarian --}}
            @if(session('error'))
                <div role="alert" class="alert text-white mt-4" style="background-color: #9CAF88;">
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Hasil Pencarian --}}
    @if(isset($reservasi))
    <div class="card bg-base-100 shadow-xl mt-8">
        <div class="card-body">
            <h2 class="card-title">Detail Reservasi Ditemukan</h2>
            <div class="divider my-1"></div>
            
            <div class="space-y-2 mt-4 text-left">
                <p><strong>ID Transaksi:</strong> {{ $reservasi['id_transaksi'] }}</p>
                <p><strong>Nama:</strong> {{ $reservasi['nama'] }}</p>
                <p><strong>Jadwal Awal:</strong> {{ \Carbon\Carbon::parse($reservasi['tanggal'])->format('d M Y') }}, Pukul {{ $reservasi['waktu'] }}</p>
            </div>
            
            {{-- Pesan Error Update (Jadwal Bentrok, dll) --}}
            @if(session('update_error'))
                <div role="alert" class="alert text-white mt-4" style="background-color: #9CAF88;">
                    <span>{{ session('update_error') }}</span>
                </div>
            @endif

            @if($bisa_reschedule)
                <div class="divider mt-6">Ubah Jadwal</div>
                
                <form action="{{ route('reschedule.update') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="id_transaksi" value="{{ $reservasi['id_transaksi'] }}">
                    
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tanggal Baru</span></label>
                        <input type="date" name="tanggal_baru" class="input input-bordered w-full" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Waktu Baru</span></label>
                        <input type="time" name="waktu_baru" class="input input-bordered w-full" max="23:00" required />
                    </div>
                    <div class="card-actions justify-end mt-4">
                        <button type="submit" class="btn btn-gradient">Update Jadwal</button>
                    </div>
                </form>
            @else
                {{-- Pesan Larangan Reschedule --}}
                <div role="alert" class="alert text-white mt-6" style="background-color: #9CAF88;">
                    <span>Jadwal tidak dapat diubah (maksimal H-1 sebelum tanggal reservasi).</span>
                </div>
            @endif
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeInput = document.querySelector('input[name="waktu_baru"]');
        if (timeInput) {
            timeInput.addEventListener('invalid', function(event) {
                if (event.target.validity.rangeOverflow) {
                    event.target.setCustomValidity('Melewati jam operasional. Harap pilih waktu sebelum 23:00.');
                }
            });
            timeInput.addEventListener('input', function(event) {
                event.target.setCustomValidity('');
            });
        }
    });
</script>
@endpush

@endsection