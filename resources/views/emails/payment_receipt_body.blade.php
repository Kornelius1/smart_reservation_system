<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .box { border: 1px solid #ddd; padding: 20px; border-radius: 8px; max-width: 600px; margin: 0 auto; }
        .header { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #eee; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="header">
            <h3>Terima Kasih, {{ $reservation->nama }}!</h3>
        </div>
        
        <p>Pembayaran Anda untuk reservasi di <strong>Homey Cafe</strong> telah berhasil dikonfirmasi.</p>
        
        <p>Berikut adalah rincian singkat transaksi Anda:</p>
        <ul>
            <li><strong>No. Invoice:</strong> {{ $reservation->id_transaksi }}</li>
            <li><strong>Total Bayar:</strong> Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</li>
            <li><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($reservation->tanggal)->format('d F Y') }}</li>
        </ul>

        <p><strong>Struk pembayaran resmi (PDF) telah kami lampirkan pada email ini.</strong> Silakan download dan simpan sebagai bukti pembayaran yang sah.</p>

        <br>
        <p>Sampai jumpa di Homey Cafe!<br>
        <small><em>Jl. Hr. Soebrantas, Panam, Pekanbaru</em></small></p>
    </div>
</body>
</html>