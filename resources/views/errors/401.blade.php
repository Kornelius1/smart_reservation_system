<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Akses Ditolak</title>
    
    <style>
        body { font-family: sans-serif; display: grid; place-items: center; min-height: 100vh; background: #f9f9f9; color: #333; }
        .container { text-align: center; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h1 { font-size: 3rem; margin: 0; color: #e74c3c; }
        p { font-size: 1.1rem; margin: 1rem 0; }
        a { display: inline-block; padding: 0.75rem 1.5rem; background: #3498db; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>401</h1>
        <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url('/') }}">Kembali ke Halaman Utama</a>
    </div>
</body>
</html>