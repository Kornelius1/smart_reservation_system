<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Landing Page Reservasi</title>
  <!-- Font Awesome (gunakan salah satu) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F2E7D5] pt-16"> <!-- pt-16 untuk offset navbar fixed -->

  <!-- NAVBAR (fixed) -->
  <nav class="bg-white shadow-md fixed w-full top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
      <!-- left: brand -->
      <a href="#home" class="text-xl font-bold text-[#819D85]">HOMEY</a>

      <!-- right: nav links + user icon (digabung supaya selalu sejajar) -->
      <div class="flex items-center space-x-6">
        <!-- menu (tersembunyi di layar kecil) -->
        <ul class="hidden md:flex items-center gap-6 text-sm font-medium text-[#6b7f6f]">
          <li><a href="#home" class="hover:text-[#819D85]">HOME</a></li>
          <li><a href="#reservasi" class="hover:text-[#819D85]">RESERVASI</a></li>
          <li><a href="#tentang" class="hover:text-[#819D85]">TENTANG KAMI</a></li>
        </ul>

        <!-- icon profil -->
        <div class="flex items-center gap-3">
          <button aria-label="User profile" class="w-8 h-8 rounded-full bg-[#F2E7D5] border border-[#9CAF88] flex items-center justify-center text-[#819D85] hover:opacity-90">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M12 12c2.7614 0 5-2.2386 5-5s-2.2386-5-5-5-5 2.2386-5 5 2.2386 5 5 5zm0 2c-3.866 0-7 3.134-7 7h2c0-2.7614 2.2386-5 5-5s5 2.2386 5 5h2c0-3.866-3.134-7-7-7z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section id="home" class="relative min-h-[70vh] flex items-center" style="background-image: url('{{ asset('../images/cover.jpg') }}'); background-size:cover; background-position:top; background-repeat:no-repeat">
    <div class="absolute inset-0 bg-black/35"></div>
    <div class="relative max-w-3xl px-6 py-20 text-white">
      <h1 class="text-4xl md:text-5xl font-serif font-semibold leading-tight mb-4">Momen Santai,<br />Meja Pasti Ada.</h1>
      <p class="text-base md:text-lg max-w-md mb-6">Cek ketersediaan meja secara real time dan amankan tempat favoritmu di Homey Cafe.</p>
      <a href="#reservasi" class="inline-block mt-2 px-6 py-3 bg-[#9CAF88] text-white rounded-md shadow hover:bg-[#819D85] transition">Reservasi Meja</a>
    </div>
  </section>

  <!-- CARA RESERVASI -->
  <section id="reservasi" class="py-16">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-semibold text-center mb-12 text-[#819D85]">Cara Reservasi</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <!-- step 1 -->
        <div class="flex flex-col items-center">
          <div class="w-14 h-14 rounded-full bg-[#9CAF88] flex items-center justify-center text-white font-semibold text-lg">1</div>
          <p class="mt-6 text-[#6E856F] text-sm leading-relaxed max-w-xs">
            Lihat denah kami, pilih meja favorit, dan tentukan jam kedatangan anda.
          </p>
        </div>
        <!-- step 2 -->
        <div class="flex flex-col items-center">
          <div class="w-14 h-14 rounded-full bg-[#9CAF88] flex items-center justify-center text-white font-semibold text-lg">2</div>
          <p class="mt-6 text-[#6E856F] text-sm leading-relaxed max-w-xs">
            Pilih menu favoritmu senilai min. Rp 30.000/orang untuk konfirmasi reservasi.
          </p>
        </div>
        <!-- step 3 -->
        <div class="flex flex-col items-center">
          <div class="w-14 h-14 rounded-full bg-[#9CAF88] flex items-center justify-center text-white font-semibold text-lg">3</div>
          <p class="mt-6 text-[#6E856F] text-sm leading-relaxed max-w-xs">
            Selesaikan pembayaran online dengan aman dan dapatkan konfirmasi reservasi secara instan.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- TENTANG -->
  <section id="tentang" class="bg-[#F2E7D5] py-12">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-8 items-center">
      <div>
        <h3 class="text-3xl font-semibold text-[#819D85] mb-4">Tentang Kami</h3>
        <p class="text-[#6E856F] leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
      <div>
        <img src="{{ asset('../images/cover.jpg') }}" alt="Tampak depan Homey Cafe" class="rounded-lg shadow-md w-full object-cover">
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-[#819D85] text-white mt-16">
    <div class="max-w-6xl mx-auto px-6 py-10 grid md:grid-cols-2 gap-8">
      <!-- Kiri -->
      <div>
        <h2 class="text-2xl font-bold">HOMEY</h2>
        <ul class="flex gap-6 mt-4 text-sm">
          <li><a href="#home" class="hover:text-gray-200">HOME</a></li>
          <li><a href="#reservasi" class="hover:text-gray-200">RESERVASI</a></li>
          <li><a href="#tentang" class="hover:text-gray-200">TENTANG KAMI</a></li>
        </ul>
      </div>
      <!-- Kanan -->
      <div class="space-y-3 text-sm">
        <p><i class="fas fa-map-marker-alt mr-2"></i> ABC Company, 123 East, 17th Street, St. Louis 10001</p>
        <p><i class="fas fa-phone mr-2"></i> (123) 456-7890</p>
        <div class="flex items-center gap-4 mt-4">
          <span>Social Media:</span>
          <a href="#" class="hover:text-gray-200 text-lg"><i class="fab fa-facebook"></i></a>
          <a href="#" class="hover:text-gray-200 text-lg"><i class="fab fa-twitter"></i></a>
          <a href="#" class="hover:text-gray-200 text-lg"><i class="fab fa-instagram"></i></a>
          <a href="#" class="hover:text-gray-200 text-lg"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
    </div>
    <div class="bg-[#6b7f6f] py-3">
      <p class="text-center text-sm">&copy; 2025 HOMEY. All Rights Reserved.</p>
    </div>
  </footer>

</body>
</html>
