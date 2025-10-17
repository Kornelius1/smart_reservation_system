{{-- resources/views/reschedule/_reservation-details.blade.php --}}
<div class="card bg-base-100 shadow-xl mt-8">
    <div class="card-body">
        <h2 class="card-title">Detail Reservasi Ditemukan</h2>
        <div class="divider my-1"></div>

        <div class="space-y-2 mt-4 text-left">
            <p><strong>ID Transaksi:</strong> {{ $reservasi['id_transaksi'] }}</p>
            <p><strong>Nama:</strong> {{ $reservasi['nama'] }}</p>
            {{-- Data tanggal sudah diformat dari controller --}}
            <p><strong>Jadwal Awal:</strong> {{ $reservasi['jadwal_awal_formatted'] }}</p>
        </div>

        @if(session('update_error'))
            <x-alert type="error" :message="session('update_error')" />
        @endif

        @if($bisa_reschedule)
            <div class="divider mt-6">Ubah Jadwal</div>
            {{-- Form update tetap di sini --}}
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
            <x-alert type="warning" message="Jadwal tidak dapat diubah (maksimal H-1 sebelum tanggal reservasi)." />
        @endif
    </div>
</div>