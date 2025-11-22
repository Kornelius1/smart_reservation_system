<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    @vite('resources/css/app.css')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="min-h-screen bg-[#F8F4E8] flex items-center justify-center p-4 font-sans">
        <div class="bg-[#788869] text-white p-8 rounded-lg shadow-xl w-full max-w-md">

            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('reservasi.confirm') }}" id="form-pembayaran">
                @csrf

                @foreach ($cartItems as $item)
                    <input type="hidden" name="items[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
                @endforeach
                @if(isset($reservationDetails))
                    @if($reservationDetails['type'] === 'ruangan')
                        <input type="hidden" name="reservation_room_name" value="{{ $reservationDetails['detail'] }}">
                    @elseif($reservationDetails['type'] === 'meja')
                        <input type="hidden" name="reservation_table_number" value="{{ $reservationDetails['detail'] }}">
                    @endif
                @endif
                <h1 class="text-3xl font-bold text-center mb-2">Konfirmasi Pesanan Anda</h1>

                @if (isset($reservationDetails))
                    <div class="mb-6 p-3 bg-white/10 rounded-lg text-center">
                        <p class="font-semibold text-sm">
                            @if($reservationDetails['type'] === 'ruangan')
                                Reservasi untuk: {{ $reservationDetails['detail'] }}
                            @elseif($reservationDetails['type'] === 'meja')
                                Reservasi untuk: Meja {{ $reservationDetails['detail'] }}
                            @endif
                        </p>
                    </div>
                @endif

                <h2 class="text-lg font-semibold mb-2">Detail Pesanan</h2>
                <div class="space-y-2 text-sm">
                    @foreach ($cartItems as $item)
                        <div class="grid grid-cols-3 gap-2 items-center">
                            <span>{{ $item['name'] }}</span>
                            <span class="text-center">{{ $item['quantity'] }} x Rp
                                {{ number_format($item['price']) }}</span>
                            <span class="text-right font-medium">Rp {{ number_format($item['subtotal']) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="divider before:bg-white/25 after:bg-white/25 my-6"></div>

                <h2 class="text-lg font-semibold mb-4">Detail Pemesan</h2>
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required minlength="3"
                            class="input input-bordered w-full bg-white/20 placeholder:text-white/50 pl-3 text-black">
                    </div>
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium mb-1">Nomor Telepon (WA)</label>
                        <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                            required placeholder="08..." pattern="^08[0-9]{8,12}$" minlength="10" maxlength="14"
                            title="Format salah. Harus dimulai dengan '08' dan total 10-14 angka. Contoh: 08123456789"
                            class="input input-bordered w-full bg-white/20 placeholder:text-white/50 pl-3 text-black">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="nama@email.com"
                            class="input input-bordered w-full bg-white/20 placeholder:text-white/50 pl-3 text-black">
                    </div>
                    <div>
                        <label for="jumlah_orang" class="block text-sm font-medium mb-1">Jumlah Orang</label>
                        <input type="number" id="jumlah_orang" name="jumlah_orang" value="{{ old('jumlah_orang') }}"
                            required min="1" max="{{ $kapasitas }}"
                            class="input input-bordered w-full bg-white/20 placeholder:text-white/50 pl-3 text-black">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required
                                class="input input-bordered w-full bg-white/20 pl-3 text-black">
                        </div>
                        <div>
                            <label for="waktu" class="block text-sm font-medium mb-1">Waktu</label>
                            <select name="waktu" id="waktu" required
                                class="input input-bordered w-full bg-white/20 pl-3 text-black">
                                <option value="">-- Pilih Waktu --</option>

                                @for ($hour = 10; $hour <= 22; $hour++)
                                    <option value="{{ sprintf('%02d:00', $hour) }}">
                                        {{ sprintf('%02d:00', $hour) }}
                                    </option>
                                @endfor
                            </select>

                        </div>
                    </div>
                </div>


                <div class="divider before:bg-white/25 after:bg-white/25 my-6"></div>

                <div class="flex justify-between font-bold text-base">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($totalPrice) }}</span>
                </div>

                <div class="mt-8">
                    <button type="button" id="tombol-bayar"
                        class="btn bg-[#364132] hover:bg-[#2a3327] border-none text-white w-full">
                        Bayar Sekarang 🛒
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="text-sm text-white/70 hover:text-white">
                    Kembali & Ubah Pesanan
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Set tanggal minimal (logika ini sudah benar)
            const tanggalInput = document.getElementById('tanggal');
            const today = new Date().toISOString().split('T')[0];
            tanggalInput.setAttribute('min', today);

            const tombolBayar = document.getElementById('tombol-bayar');
            const form = document.getElementById('form-pembayaran');

            tombolBayar.addEventListener('click', async function (event) {
                console.log('TOMBOL DIKLIK!');
                event.preventDefault();

                // 1. Validasi Frontend (SweetAlert untuk error)
                if (!form.checkValidity()) {
                    // Temukan input pertama yang tidak valid
                    const firstInvalidField = form.querySelector(':invalid');
                    let errorMessage = 'Silakan isi semua data pemesan dengan benar.';

                    if (firstInvalidField) {
                        // Ambil pesan error dari 'title' (untuk No. Telp) atau 'label'
                        const label = firstInvalidField.labels[0]?.textContent || 'Kolom';
                        errorMessage = `Input '${label}' sepertinya belum valid. ${firstInvalidField.title || ''}`;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: errorMessage,
                    });

                    // (Opsional) Fokus ke field pertama yang error
                    // firstInvalidField?.focus(); 
                    return;
                }

                // --- Validasi Bisnis Sederhana (Opsional) ---
                // Catatan: Seharusnya ini sudah divalidasi oleh controller 'show()',
                // tapi ini adalah 'pertahanan lapis kedua' yang baik.
                @if (count($cartItems) === 0)
                    Swal.fire({
                        icon: 'error',
                        title: 'Keranjang Kosong',
                        text: 'Anda tidak bisa membayar dengan keranjang kosong.',
                    });
                    return;
                @endif


                // 2. Tampilkan Loading (SweetAlert untuk proses)
                Swal.fire({
                    title: 'Memproses Pembayaran...',
                    text: 'Mohon tunggu, Anda akan diarahkan ke halaman pembayaran.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // 3. Kirim Data (Fetch API)
                const formData = new FormData(form);
                const url = form.action;
                const csrfToken = form.querySelector('input[name="_token"]').value;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json' // Penting: Minta JSON
                        },
                        body: formData
                    });

                    const responseData = await response.json();

                    if (response.ok) {
                        // 4. Sukses (Redirect ke DOKU)
                        // SweetAlert akan otomatis tertutup saat halaman redirect
                        window.location.href = responseData.payment_url;
                    } else {
                        // 5. Gagal dari Backend (SweetAlert untuk error)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops! Terjadi Kesalahan',
                            // 'message' adalah standar error JSON Laravel
                            text: responseData.message || 'Gagal membuat pembayaran. Silakan coba lagi.'
                        });
                    }
                } catch (error) {
                    // 6. Gagal Jaringan (SweetAlert untuk error)
                    console.error('Fetch Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan',
                        text: 'Tidak dapat terhubung ke server. Periksa koneksi Anda.'
                    });
                }
            });
        });
    </script>
</body>

</html>