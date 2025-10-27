<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Berhasil!</title>
    @vite('resources/css/app.css') {{-- Menggunakan Vite --}}
</head>
<body>
    <div class="min-h-screen bg-[#F8F4E8] flex items-center justify-center p-4 font-sans">
        <div class="bg-[#788869] text-white p-8 rounded-lg shadow-xl w-full max-w-md text-center">

            {{-- Cek apakah ada pesan sukses dari controller --}}
            @if (session('success_message'))
                
                {{-- Icon Sukses (Checkmark) --}}
                <svg class="w-20 h-20 text-white mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                  
                <h1 class="text-3xl font-bold mb-3">Reservasi Berhasil!</h1>

                {{-- Menampilkan pesan dinamis dari controller --}}
                <p class="mb-8 text-white/80">
                    {{ session('success_message') }}
                </p>

                {{-- Tombol Aksi --}}
                <a href="/" {{-- Arahkan ke Halaman Utama/Beranda --}}
                   class="btn bg-[#364132] hover:bg-[#2a3327] border-none text-white w-full">
                   Kembali ke Beranda
                </a>
            
            @else
                {{-- Jika halaman diakses langsung tanpa reservasi --}}
                <h1 class="text-3xl font-bold mb-3">Oops!</h1>
                <p class="mb-8 text-white/80">
                    Sepertinya Anda mengakses halaman ini secara langsung.
                </p>
                <a href="/" 
                   class="btn bg-[#364132] hover:bg-[#2a3327] border-none text-white w-full">
                   Kembali ke Beranda
                </a>
            @endif

        </div>
    </div>
</body>
</html>