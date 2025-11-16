<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Reservasi {{ $reservation->id_transaksi }}</title>
    <style>
        body { font-family: sans-serif; margin: 25px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { width: 150px; }
        .header h1 { margin: 0; font-size: 24px; }
        .details { margin-top: 30px; border-collapse: collapse; width: 100%; }
        .details th, .details td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .details th { background-color: #f4f4f4; }
        .total { font-weight: bold; font-size: 1.2em; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        {{-- Ganti 'path/ke/logo.png' dengan URL logo Anda --}}
        {{-- <img src="{{ public_path('images/logo.png') }}" alt="Logo Homey Caffe"> --}}
        <h1>Homey Caffe</h1>
        <p>Bukti Reservasi</p>
    </div>

    <h3>Detail Pelanggan</h3>
    <table class="details">
        <tr>
            <th>No. Invoice</th>
            <td>{{ $reservation->id_transaksi }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $reservation->nama }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td style="font-weight: bold; color: green;">
                {{-- Logika status yang sama dengan di controller --}}
                @if ($reservation->status === 'akan datang' || $reservation->status === 'check-in')
                    PEMBAYARAN SUKSES
                @else
                    {{ $reservation->status }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Tanggal Reservasi</th>
            <td>{{ $reservation->tanggal->format('d F Y') }} jam {{ \Carbon\Carbon::parse($reservation->waktu)->format('H:i') }} WIB</td>
        </tr>
    </table>

    <h3>Detail Pesanan</h3>
    <table class="details">
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservation->products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->pivot->quantity }}x</td>
                <td>Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="3" style="text-align: right;">Total Pembayaran</td>
                <td>Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Terima kasih atas reservasi Anda.
    </div>
</body>
</html>