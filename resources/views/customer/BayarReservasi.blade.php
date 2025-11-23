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
                                <option value="">-- Pilih Tanggal Dulu --</option>
                            </select>
                            <div id="loading-times" class="text-sm text-blue-500 mt-1 hidden">Memuat jam tersedia...
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Referensi Elemen ---
        const tanggalInput = document.getElementById('tanggal');
        const tombolBayar = document.getElementById('tombol-bayar');
        const form = document.getElementById('form-pembayaran');
        const waktuSelect = document.getElementById('waktu');
        const loadingIndicator = document.getElementById('loading-times');
        
        // Ambil elemen input hidden
        const mejaInput = document.querySelector('input[name="reservation_table_number"]');
        const ruanganInput = document.querySelector('input[name="reservation_room_name"]');

        // Pastikan elemen kritis ada sebelum lanjut
        if (!tombolBayar || !form || !waktuSelect || !tanggalInput) return;

        // --- 2. Setup Awal (Tanggal Min & Data Blade) ---
        const today = new Date().toISOString().split('T')[0];
        tanggalInput.setAttribute('min', today);

        // Data dari Laravel Blade (Safe JSON)
        const mejaList = @json(isset($mejas) ? $mejas->map(fn($m) => ['id' => $m->id, 'nomor_meja' => $m->nomor_meja])->toArray() : []);
        const roomList = @json(isset($rooms) ? $rooms->map(fn($r) => ['id' => $r->id, 'nama_ruangan' => $r->nama_ruangan])->toArray() : []);

        // --- 3. Helper: Identifikasi Pilihan ---
        function getReservationSelection() {
            const tableNumber = mejaInput ? mejaInput.value : null;
            const roomName = ruanganInput ? ruanganInput.value : null;

            if (tableNumber) {
                // Konversi ke string agar aman saat membandingkan
                const meja = mejaList.find(m => String(m.nomor_meja) === String(tableNumber));
                return meja ? { type: 'meja', id: meja.id } : null;
            }

            if (roomName) {
                const room = roomList.find(r => r.nama_ruangan === roomName);
                return room ? { type: 'ruangan', id: room.id } : null;
            }

            return null;
        }

        // --- 4. Core: Muat Jam Tersedia ---
        async function loadAvailableTimes() {
            const tanggal = tanggalInput.value;
            const selection = getReservationSelection();

            // Reset dropdown
            waktuSelect.innerHTML = '<option value="">-- Pilih Waktu --</option>';
            
            // Validasi: Jangan fetch jika tanggal kosong atau jenis reservasi tidak terdeteksi
            if (!tanggal || !selection) return;

            // UI Loading
            if (loadingIndicator) loadingIndicator.classList.remove('hidden');
            waktuSelect.disabled = true; // Disable dropdown saat loading

            try {
                const response = await fetch('{{ route("check-available-times") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tanggal: tanggal,
                        reservation_type: selection.type,
                        id: selection.id
                    })
                });

                const data = await response.json();

                // Populate Dropdown
                if (data.available_times && data.available_times.length > 0) {
                    data.available_times.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        waktuSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'Tidak ada jam tersedia. Silahkan pilih tanggal lain';
                    option.disabled = true;
                    waktuSelect.appendChild(option);
                }
            } catch (err) {
                console.error('API Error:', err);
                const option = document.createElement('option');
                option.textContent = 'Gagal memuat jam';
                waktuSelect.appendChild(option);
            } finally {
                // UI Reset
                if (loadingIndicator) loadingIndicator.classList.add('hidden');
                waktuSelect.disabled = false;
            }
        }

        // --- 5. Event Listeners ---
        tanggalInput.addEventListener('change', loadAvailableTimes);
        
        // Panggil sekali saat load (jika user back, atau ada value default)
        if (tanggalInput.value) {
            loadAvailableTimes();
        }

        // --- 6. Logika Pembayaran ---
        tombolBayar.addEventListener('click', async function(event) {
            event.preventDefault();

            // Validasi HTML5 Native
            if (!form.checkValidity()) {
                form.reportValidity(); // Memunculkan tooltip browser bawaan
                return;
            }

            // Validasi Keranjang Kosong (Blade Logic)
            @if(isset($cartItems) && count($cartItems) === 0)
                Swal.fire('Keranjang Kosong', 'Anda tidak bisa membayar dengan keranjang kosong.', 'error');
                return;
            @endif

            // UX: Loading State & Disable Button
            Swal.fire({
                title: 'Memproses Pembayaran...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            tombolBayar.disabled = true;
            tombolBayar.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.payment_url) {
                    window.location.href = result.payment_url;
                } else {
                    throw new Error(result.message || 'Gagal memproses pembayaran');
                }
            } catch (error) {
                console.error('Payment Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan jaringan.'
                });
                
                // Re-enable button jika error
                tombolBayar.disabled = false;
                tombolBayar.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    });
</script>
</body>

</html>