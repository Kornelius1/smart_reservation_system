<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Struk Reservasi {{ $reservation->id_transaksi }}</title>

    <style>
        /* CSS Internal untuk dompdf */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 14px;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        /* * CSS UNTUK STEMPEL/WATERMARK
         */
        .watermark {
            position: fixed;
            /* Penting untuk dompdf */
            top: 35%;
            /* Posisikan di tengah vertikal */
            left: 50%;
            /* Posisikan di tengah horizontal */
            /* Pindah ke tengah & putar */
            transform: translate(-50%, -50%) rotate(-20deg);
            opacity: 0.15;
            /* Atur transparansi */
            z-index: -1000;
            /* Pastikan di belakang semua teks */
            width: 300px;
            /* Ukuran stempel */
            height: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0;
            font-size: 16px;
        }

        /* Menggunakan <table> untuk layout info */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }

        .info-table th {
            background-color: #f9f9f9;
            width: 180px;
            /* Memberi lebar tetap pada label */
            text-align: left;
        }

        /* Tabel untuk item pesanan */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .items-table thead th {
            background-color: #333;
            color: #fff;
            padding: 12px;
            text-align: left;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .items-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .items-table .total-row td {
            font-size: 1.15em;
            font-weight: bold;
            border-top: 2px solid #333;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>

    {{--
    BLOK PHP UNTUK MEMBUAT WATERMARK
    (Pastikan 'public/images/homey.svg' ada)
    --}}
    @php
        $logoPath = public_path('images/homey.svg');
        $imgSrcWatermark = '';
        if (file_exists($logoPath)) {
            $svgContent = file_get_contents($logoPath);
            $imgSrcWatermark = 'data:image/svg+xml;base64,' . base64_encode($svgContent);
        }
    @endphp

    {{-- Tampilkan watermark jika berhasil dibuat --}}
    @if ($imgSrcWatermark)
        <img src="{{ $imgSrcWatermark }}" class="watermark" alt="Homey Caffe Watermark">
    @endif


    <div class="container">

        <div class="header">
            {{-- Logo sudah dipindah ke watermark, jadi di sini hanya teks --}}
            <h1>Homey Caffe</h1>
            <p>Bukti Reservasi (LUNAS)</p>
        </div>

        <h3>Detail Reservasi</h3>

        <table class="info-table">
            <tr>
                <th>No. Invoice</th>
                <td>{{ $reservation->id_transaksi }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td style="font-weight: bold; color: green;">
                    @if ($reservation->status === 'akan datang' || $reservation->status === 'check-in')
                        PEMBAYARAN SUKSES
                    @else
                        {{ $reservation->status }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Nama Pemesan</th>
                <td>{{ $reservation->nama }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $reservation->email_customer }}</td>
            </tr>
            <tr>
                <th>Telepon</th>
                <td>{{ $reservation->nomor_telepon }}</td>
            </tr>
            <tr>
                <th>Tanggal & Waktu</th>
                <td>
                    {{-- [PERBAIKAN] Gabungkan tanggal (Objek) dan waktu (String) --}}

                    {{ \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->waktu)->format('l, d F Y, H:i') }}
                    WIB
                </td>
                /tr>
            <tr>
                <th>Reservasi Tempat</th>
                <td>
                    @if ($reservation->nomor_meja)
                        Meja Nomor {{ $reservation->nomor_meja }}
                    @elseif ($reservation->nomor_ruangan)
                        Ruangan ID {{ $reservation->nomor_ruangan }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>

        <h3>Rincian Pesanan</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservation->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="text-right">{{ $product->pivot->quantity }}x</td>
                        <td class="text-right">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp
                            {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total Pembayaran</td>
                    <td class="text-right">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            Harap tunjukkan struk ini saat tiba di lokasi. Terima kasih.
        </div>
    </div>
</body>

</html>