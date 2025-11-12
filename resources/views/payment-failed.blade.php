<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal</title>
    <style>
        body {
            font-family: sans-serif;
            display: grid;
            place-items: center;
            min-height: 90vh;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            text-align: center;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h1 {
            color: #dc3545;
            margin-bottom: 1rem;
        }

        p {
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        a {
            color: #fff;
            background-color: #007bff;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Pembayaran Gagal</h1>
        <p>
            Maaf, terjadi masalah dengan pembayaran Anda (invoice <strong>{{ $invoice ?? 'Anda' }}</strong>) atau Anda
            telah membatalkannya.




            Silakan coba lagi atau hubungi dukungan kami jika masalah berlanjut.
        </p>
        <a href="/">Kembali ke Beranda</a>
    </div>
</body>

</html>