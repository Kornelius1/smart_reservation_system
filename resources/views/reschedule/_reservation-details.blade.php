<div class="card bg-base-100 shadow-xl mt-8">
    <div class="card-body">
        <h2 class="card-title">Detail Reservasi Ditemukan</h2>
        <div class="divider my-1"></div>

        <div class="space-y-2 mt-4 text-left">
            <p><strong>ID Transaksi:</strong> {{ $reservasi->id_transaksi }}</p>
            <p><strong>Nama:</strong> {{ $reservasi->nama }}</p>
            <p><strong>Jadwal Awal:</strong> {{ $reservasi->jadwal_awal_formatted }}</p>

            {{-- ============================================= --}}
            {{-- PERBAIKAN 1: TAMPILKAN STATUS SAAT INI --}}
            {{-- ============================================= --}}
            <p><strong>Status:</strong>
                @switch($reservasi->status)
                    @case('Akan Datang')
                        <span class="badge badge-info text-white">Akan Datang</span>
                        @break
                    @case('Berlangsung')
                        <span class="badge badge-success text-white">Berlangsung</span>
                        @break
                    @case('Selesai')
                        <span class="badge badge-ghost">Selesai</span>
                        @break
                    @case('Dibatalkan')
                        <span class="badge badge-error text-white">Dibatalkan</span>
                        @break
                    @case('Tidak Datang')
                        <span class="badge badge-warning text-white">Tidak Datang</span>
                        @break
                    @default
                        <span class="badge">{{ $reservasi->status }}</span>
                @endswitch
            </p>
        </div>

        {{-- Error untuk logika bisnis (dari controller) --}}
        @if(session('update_error'))
            <x-alert type="error" :message="session('update_error')" />
        @endif

        {{-- Error validasi standar --}}
        @if ($errors->any())
            <div class="alert alert-error shadow-lg my-4">
                <div>
                    <span>Data yang Anda masukkan tidak valid:</span>
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        @if($bisa_reschedule)
            <div class="divider mt-6">Ubah Jadwal</div>
            
            {{-- ============================================= --}}
            {{-- PERBAIKAN 2: TAMBAHKAN @method('PATCH') --}}
            {{-- ============================================= --}}
            <form action="{{ route('reschedule.update') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PATCH') {{-- <-- WAJIB DITAMBAHKAN --}}

                <input type="hidden" name="id_transaksi" value="{{ $reservasi->id_transaksi }}">
                
                <div class="form-control">
                    <label class="label"><span class="label-text">Tanggal Baru</span></label>
                    <input type="date" name="tanggal_baru" class="input input-bordered w-full" 
                           min="{{ now()->format('Y-m-d') }}" 
                           value="{{ old('tanggal_baru', now()->format('Y-m-d')) }}" required />
                </div>
                
                <div class="form-control">
                    <label class="label"><span class="label-text">Waktu Baru</span></label>
                    <input type="time" name="waktu_baru" class="input input-bordered w-full" 
                           min="10:00" max="23:00" 
                           value="{{ old('waktu_baru', '19:00') }}" required />
                    <label class="label">
                        <span class="label-text-alt">Jam operasional: 10:00 - 23:00</span>
                    </label>
                </div>
                
                <div class="card-actions justify-end mt-4">
                    <button type="submit" class="btn btn-gradient">Update Jadwal</button>
                </div>
            </form>
        @else
            {{-- ========================================================= --}}
            {{-- PERBAIKAN 3: GUNAKAN PESAN ERROR DINAMIS DARI CONTROLLER --}}
            {{-- ========================================================= --}}
            {{-- 
              Kode Lama (Salah):
              <x-alert type="warning" message="Jadwal tidak dapat diubah (maksimal H-1 sebelum tanggal reservasi)." /> 
            --}}
            
            {{-- Kode Baru (Benar): --}}
            <x-alert type="warning" :message="$alasan_tidak_bisa ?? 'Reservasi ini tidak dapat di-reschedule.'" />

        @endif
    </div>
</div>