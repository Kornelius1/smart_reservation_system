<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Akses Ditolak</title>
    
    <style>
        body { 
            font-family: sans-serif; 
            display: grid; 
            place-items: center; 
            min-height: 100vh; 
            background: #dfe6da; 
            color: #414939; 
        }
        .container { 
            text-align: center; 
            padding: 2rem; 
            background: #ffffff; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
        }
        h1 { 
            font-size: 3rem; 
            margin: 0; 
            color: #f87272; 
        }
        p { 
            font-size: 1.1rem; 
            margin: 1rem 0; 
        }

        a {
            display: inline-block;
            padding: 0.75rem 1.5rem;
  
            background-image: linear-gradient(to right, #9caf88, #414939);
            border: none; 
            color: #ffffff; 

            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        a:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }
       

    </style>
</head>
<body>
    <div class="container">
        <h1>402</h1>
        <p>Pembayaran Diperlukan</p>
        <a href="{{ url('/') }}">Kembali ke Halaman Utama</a>
    </div>
</body>
</html>