@extends('layouts.appnofooter')

@section('title', 'Pembayaran Sukses')

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12 min-h-screen flex flex-col items-center justify-center">
        <div class="max-w-lg w-full bg-white shadow-xl rounded-lg overflow-hidden border">
            <!-- Header seperti struk -->
            <div class="bg-gray-800 text-white p-4 text-center">
                <h2 class="font-mono font-bold text-lg">{{ $app_name ?? 'Homey Cafe' }}</h2>
                <p class="font-mono text-xs mt-1">{{ $alamat ?? 'Jl. Mawar, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau 28293' }}</p>
                <p class="font-mono text-xs">{{ $telp ?? 'Ig: @homey.cafe' }}</p>
            </div>

            <div class="p-4 md:p-6">
                @if ($isSuccess)
                    <div class="text-center mb-4">
                        <svg class="w-16 h-16 mx-auto text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h1 class="text-2xl font-bold text-success mt-3">✓ Pembayaran Berhasil</h1>
                        <p class="text-gray-600 text-sm">{{ $statusMessage }}</p>
                    </div>

                    <!-- Preview struk (monospace, compact) -->
                    <div class="bg-gray-50 rounded border font-mono text-sm p-4 text-left overflow-x-auto">
                        <div class="text-center font-bold mb-2">STRUK PEMBAYARAN</div>
                        <hr class="border-t border-dashed border-gray-500 my-1">

                        <div class="grid grid-cols-[1fr_auto] gap-y-1 text-xs">
                            <span>No. Invoice</span><span class="text-right">{{ $reservation->id_transaksi }}</span>
                            <span>Pelanggan</span><span class="text-right">{{ $reservation->nama ?? '-' }}</span>
                            <span>Tanggal</span>
                            <span class="text-right">
                                @php
                                    $dt = $reservation->tanggal instanceof \Carbon\CarbonInterface
                                        ? $reservation->tanggal->copy()->setTimeFromTimeString($reservation->waktu)
                                        : \Carbon\Carbon::parse($reservation->tanggal . ' ' . $reservation->waktu);
                                @endphp
                                {{ $dt->format('d/m/Y H:i') }}
                            </span>
                            <span>Lokasi</span>
                            <span class="text-right">
                                @if($reservation->nomor_meja && $reservation->nomor_meja !== '0')
                                    Meja {{ $reservation->nomor_meja }}
                                @elseif($reservation->nomor_ruangan && $reservation->nomor_ruangan !== '0')
                                    Ruang {{ $reservation->nomor_ruangan }}
                                @else
                                    Umum
                                @endif
                            </span>
                        </div>

                        <hr class="border-t border-dashed border-gray-500 my-2">

                        <!-- Produk -->
                        @foreach($reservation->products as $product)
                            @php
                                $qty = $product->pivot->quantity;
                                $price = $product->pivot->price;
                                $subtotal = $qty * $price;
                                // Batasi nama agar muat (~24 char)
                                $name = substr($product->name, 0, 24);
                            @endphp
                            <div class="flex justify-between text-xs">
                                <span>{{ $name }} x{{ $qty }}</span>
                                <span class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <hr class="border-t border-dashed border-gray-500 my-2">

                        <div class="flex justify-between font-bold text-sm">
                            <span>TOTAL</span>
                            <span class="text-right">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                        </div>

                        <hr class="border-t border-gray-700 my-2">

                        <div class="text-center text-xs mt-2 text-gray-600">
                            Terima kasih atas kunjungan Anda!<br>
                            Simpan struk ini sebagai bukti transaksi.<br>
                            {{ now()->format('d/m/Y H:i') }} WIB
                        </div>
                    </div>

                    <!-- Tombol Cetak (PDF) -->
                    <div class="mt-6">
                        <a href="{{ route('payment.receipt.pdf', ['invoice' => $reservation->id_transaksi]) }}"
                           class="btn btn-primary w-full">
                            <i class="fas fa-print mr-2"></i> Cetak / Simpan Struk (PDF)
                        </a>
                    </div>

                @else
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.332 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <h1 class="text-2xl font-bold text-warning mt-3">⚠ Menunggu Konfirmasi</h1>
                        <p class="text-gray-600">{{ $statusMessage }}</p>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('customer.landing.page') }}" class="btn btn-outline w-full">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection