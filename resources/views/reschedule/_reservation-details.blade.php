<div class="card bg-base-100 shadow-xl mt-8">
    <div class="card-body">
        <h2 class="card-title">Detail Reservasi Ditemukan</h2>
        <div class="divider my-1"></div>

        <div class="space-y-2 mt-4 text-left">
            {{-- PERUBAHAN 1: Gunakan sintaks Objek ->key --}}
            <p><strong>ID Transaksi:</strong> {{ $reservasi->id_transaksi }}</p>
            <p><strong>Nama:</strong> {{ $reservasi->nama }}</p>
            <p><strong>Jadwal Awal:</strong> {{ $reservasi->jadwal_awal_formatted }}</p>
        </div>

        {{-- Error untuk logika bisnis (dari controller) --}}
        @if(session('update_error'))
            <x-alert type="error" :message="session('update_error')" />
        @endif

        {{-- PERUBAHAN 2: Tambahkan blok untuk error validasi standar --}}
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
            
            <form action="{{ route('reschedule.update') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                {{-- PERUBAHAN 1 (Lanjutan) --}}
                <input type="hidden" name="id_transaksi" value="{{ $reservasi->id_transaksi }}">
                
                <div class="form-control">
                    <label class="label"><span class="label-text">Tanggal Baru</span></label>
                    {{-- PERUBAHAN 3: Tambah min (validasi) dan value (UX) --}}
                    <input type="date" name="tanggal_baru" class="input input-bordered w-full" 
                           min="{{ now()->format('Y-m-d') }}" 
                           value="{{ old('tanggal_baru', now()->format('Y-m-d')) }}" required />
                </div>
                
                <div class="form-control">
                    <label class="label"><span class="label-text">Waktu Baru</span></label>
                    {{-- PERUBAHAN 4: Tambah min (konsistensi) dan value (UX) --}}
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
            {{-- Ini sudah benar --}}
            <x-alert type="warning" message="Jadwal tidak dapat diubah (maksimal H-1 sebelum tanggal reservasi)." />
        @endif
    </div>
</div>