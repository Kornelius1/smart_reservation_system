@extends('layouts.app') {{-- <-- INI KUNCINYA. JANGAN SAMPAI LUPA --}}

@section('content')

<div class="max-w-2xl mx-auto py-10 px-4">

    {{-- JUDUL HALAMAN --}}
    <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
        Reschedule Reservasi Anda
    </h1>

    {{-- KOTAK FORM PENCARIAN --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Cari Reservasi</h2>
            <p>Masukkan ID Transaksi untuk menemukan detail reservasi Anda.</p>

            <form action="{{ route('reschedule.find') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-control">
                    <div class="join w-full">
                        <input type="text" name="id_transaksi" placeholder="Contoh: TRS001" class="input input-bordered join-item w-full" value="{{ old('id_transaksi', $reservasi['id_transaksi'] ?? '') }}" required />
                        <button type="submit" class="btn btn-primary join-item">Cari</button>
                    </div>
                </div>
            </form>

            {{-- Tampilkan pesan error jika ada --}}
            @if(session('error'))
                <div role="alert" class="alert alert-error mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- HASIL PENCARIAN (HANYA TAMPIL JIKA DATA DITEMUKAN) --}}
    @if(isset($reservasi))
    <div class="card bg-base-100 shadow-xl mt-8">
        <div class="card-body">
            <h2 class="card-title">Detail Reservasi Ditemukan</h2>
            <div class="divider my-1"></div>
            {{-- Tampilkan detail --}}
            <div class="space-y-2 mt-4 text-left">
                <p><strong>ID Transaksi:</strong> {{ $reservasi['id_transaksi'] }}</p>
                <p><strong>Nama:</strong> {{ $reservasi['nama'] }}</p>
                <p><strong>Jadwal Awal:</strong> {{ \Carbon\Carbon::parse($reservasi['tanggal'])->format('d M Y') }}, Pukul {{ $reservasi['waktu'] }}</p>
            </div>

            {{-- Cek apakah boleh reschedule --}}
            @if($bisa_reschedule)
                <div class="divider mt-6">Ubah Jadwal</div>
                <form action="#" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tanggal Baru</span></label>
                        <input type="date" name="tanggal_baru" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Waktu Baru</span></label>
                        <input type="time" name="waktu_baru" class="input input-bordered w-full" />
                    </div>
                    <div class="card-actions justify-end mt-4">
                        <button class="btn btn-success">Update Jadwal</button>
                    </div>
                </form>
            @else
                <div role="alert" class="alert alert-warning mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>Jadwal tidak dapat diubah (maksimal H-1 sebelum tanggal reservasi).</span>
                </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection