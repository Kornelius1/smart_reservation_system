<div class="p-4 lg:p-8" wire:poll.5s>

    @push('styles')
        <style>
            .toggle {
                --toggle-handle-color: white !important;
            }

            .toggle:checked {
                background-image: none !important;
            }
        </style>
    @endpush

    {{-- 
        Konten di bawah ini sebagian besar tetap sama, 
        kecuali <input> pencarian.
    --}}
    
    <div class="flex items-center gap-3 mb-8">
        <button onclick="window.history.back()" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="text-2xl">Manajemen Reservasi</h1>
    </div>

    {{-- ============================================= --}}
    {{-- BLOK ALERT UNTUK NOTIFIKASI SUKSES/ERROR --}}
    {{-- ============================================= --}}
    @if (session('success'))
        <div role="alert" class="alert alert-success mb-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div role="alert" class="alert alert-error mb-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    {{-- ============================================= --}}

    <div class="card w-full bg-white shadow-xl">
        <div class="card-body">
            <h1 class="text-2xl font-bold brand-text-1 border-b-4 border-brand-primary pb-2">MANAJEMEN RESERVASI</h1>

            {{-- 
            ======================================================================
            2. PERUBAHAN INPUT PENCARIAN
               'id="searchInput"' diganti dengan 'wire:model.live="search"'
            ======================================================================
            --}}
            <div class="form-control relative my-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 absolute left-3 top-1.5 text-gray-500"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live="search" type="text" placeholder="Cari berdasarkan nama customer..."
                       class="input input-sm input-bordered w-full sm:w-72 pl-10" />
            </div>


            <div class="overflow-x-auto mt-4">

                <table id="tableData" class="table w-full">
                    {{-- HEADER TABEL (SAMA SEPERTI YANG ANDA BERIKAN) --}}
                    <thead>
                        <tr class="brand-text-1 text-center" style="background-color: #C6D2B9;">
                            {{-- <th>ID Reservasi</th> --}}
                            <th>ID Transaksi</th>
                            <th>Nomor Meja</th>
                            <th>Nomor Ruangan</th>
                            <th>Nama Customer</th>
                            <th>Nomor Telepon</th>
                            <th>Jumlah Orang</th>
                            <th>Daftar Pesanan</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Waktu Reservasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    {{-- ISI TABEL (SAMA SEPERTI YANG ANDA BERIKAN) --}}
                    <tbody class="text-brand-text">
                        @forelse ($reservations as $reservation)
                            @php
                                // Waktu di set ke 60 detik (1 menit)
                                $isNew = $reservation->status == 'akan datang' && $reservation->created_at->diffInSeconds(now()) < 60;
                            @endphp
                           <tr class="text-center {{ $isNew ? 'bg-yellow-50/70 border-l-4 border-yellow-500 transition-colors duration-1000' : '' }}">
                                {{-- <th>{{ $reservation->id_reservasi }}</th> --}}
                                <td>{{ $reservation->id_transaksi }}</td>
                                <td>{{ $reservation->nomor_meja ?? '-' }}</td>
                                <td>{{ $reservation->nomor_ruangan ?? '-' }}</td>
                                <td class="whitespace-nowrap">{{ $reservation->nama }}</td>
                                <td>{{ $reservation->nomor_telepon ?? '-' }}</td>
                                <td>{{ $reservation->jumlah_orang ?? '-' }} Orang</td>
                                
                                {{-- Kolom Daftar Pesanan --}}
                                <td class="text-xs text-left">
                                    @if ($reservation->products->isEmpty())
                                    <span>-</span>
                                    @else
                                    {{-- Tombol untuk membuka modal --}}
                                            <button class="btn btn-xs btn-ghost text-blue-600" onclick="modal_{{ $reservation->id_reservasi }}.showModal()">
                                    Lihat ({{ $reservation->products->count() }} item)</button>
                                    @endif
                                </td>

                                <td>Rp {{ number_format($reservation->total_price) }}</td>
                                <td>{{ $reservation->tanggal ? $reservation->tanggal->format('d-m-Y') : '-' }}</td>
                                <td>{{ $reservation->waktu ? \Carbon\Carbon::parse($reservation->waktu)->format('H:i') : '-' }} WIB</td>
                                
                            
                                {{-- Blok Status (SAMA SEPERTI YANG ANDA BERIKAN) --}}
                                <td class="whitespace-nowrap">
                                    @switch($reservation->status)
                                        @case('pending')
                                            <span class="badge badge-warning text-white badge-sm">Belum Dibayar</span>
                                            @break
                                        
                                        @case('akan datang')
                                            <span class="badge badge-info text-white badge-sm">Akan Datang</span>
                                            @break

                                        @case('check-in')
                                            <span class="badge badge-success text-white badge-sm">Berlangsung</span>
                                            @break

                                        @case('selesai')
                                            <span class="badge badge-ghost badge-sm">Selesai</span>
                                            @break

                                        @case('dibatalkan')
                                            <span class="badge badge-error text-white badge-sm">Dibatalkan</span>
                                            @break

                                        @case('kedaluwarsa')
                                        <span class="badge badge-neutral text-white badge-sm">Kedaluwarsa</span>
                                        @break

                                        @default
                                            <span class="badge">{{ $reservation->status }}</span>
                                    @endswitch
                                </td>
                                
                                {{-- Blok Aksi (SAMA SEPERTI YANG ANDA BERIKAN) --}}
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        @if ($reservation->status == 'akan datang')
                                            {{-- Form untuk Check-in --}}
                                            <form action="{{ route('admin.reservasi.checkin', $reservation->id_reservasi) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-xs text-white">Check-in</button>
                                            </form>

                                            {{-- Form untuk Batalkan --}}
                                            <form action="{{ route('admin.reservasi.cancel', $reservation->id_reservasi) }}" method="POST" class="m-0"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin MEMBATALKAN reservasi ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-error btn-xs text-white">Batalkan</button>
                                            </form>
                                        
                                        @elseif ($reservation->status == 'pending')
                                            {{-- Form untuk Batalkan (hanya pending) --}}
                                            <form action="{{ route('admin.reservasi.cancel', $reservation->id_reservasi) }}" method="POST" class="m-0"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin MEMBATALKAN reservasi ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-error btn-xs text-white">Batalkan</button>
                                            </form>

                                        @elseif ($reservation->status == 'check-in')
                                            {{-- Form untuk Selesaikan --}}
                                            <form action="{{ route('admin.reservasi.complete', $reservation->id_reservasi) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-primary btn-xs text-white">Selesaikan</button>
                                            </form>

                                        @else
                                            {{-- Status Selesai, Dibatalkan --}}
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Jika tidak ada data --}}
                            <tr>
                                <td colspan="12" class="text-center py-4">Tidak ada data reservasi ditemukan.</td>
                            </tr>

                          {{-- MODAL (Ditempatkan di luar <tr> tapi di dalam @forelse) --}}
                            @if (!$reservation->products->isEmpty())
                                <dialog id="modal_{{ $reservation->id_reservasi }}" class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg text-brand-text">Daftar Pesanan</h3>
                                        <p class="py-2 text-brand-text">Invoice: {{ $reservation->id_transaksi }}</p>
                                        
                                        <ul class="list-disc list-inside py-4 text-brand-text text-left">
                                            @foreach ($reservation->products as $product)
                                                <li class="text-sm">
                                                    {{ $product->name }} 
                                                    <span class="font-semibold">
                                                        ({{ $product->pivot->quantity }}x @ Rp {{ number_format($product->pivot->price) }})
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="modal-action">
                                            <form method="dialog">
                                                <button class="btn">Tutup</button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    {{-- Klik di luar untuk menutup --}}
                                    <form method="dialog" class="modal-backdrop">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            @endif
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
