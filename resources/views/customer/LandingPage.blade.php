<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Landing Page')</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/css/component.css', 'resources/js/app.js'])
  {{-- @stack('styles') --}}
</head>

<body>
  @include('layouts.partials.landingnavbar')

  <div>
    <!-- HERO -->
    <section id="home" class="relative min-h-[83vh] flex items-center"
      style="background-image: url('{{ asset('../images/cover.webp') }}'); background-size:cover; background-position:top; background-repeat:no-repeat">
      <div class="absolute inset-0 bg-black/35"></div>
      <div class="relative max-w-3xl px-20 py-20 text-white">
        <h1 class="text-4xl md:text-5xl font-serif font-semibold leading-tight mb-4">Momen Santai,<br />Meja Pasti Ada.
        </h1>
        <p class="text-base md:text-lg max-w-md mb-6">Cek ketersediaan meja secara real time dan amankan tempat
          favoritmu
          di Homey Cafe.</p>
        <ul>
          <li>
            <a href="/pilih-meja"
              class="inline-block mt-2 px-6 py-3 btn-gradient rounded-md hover:bg-[#819D85] transition">Reservasi
              Meja</a>
            <a href="/reservasi-ruangan"
              class="inline-block mt-2 px-6 py-3 btn-gradient rounded-md hover:bg-[#819D85] transition">Reservasi
              Ruangan</a>
          </li>
          <li>
            <a href="/reschedule"
              class="inline-block mt-2 px-6 py-3 btn-gradient rounded-md hover:bg-[#819D85] transition">Reschedule
              Jadwal</a>
          </li>
        </ul>
      </div>
    </section>

    <div class="relative z-10 -mt-5 flex justify-center">
      <a class="scroll" href="#reservasi">
        <img src="{{ asset('../images/arrow-green.svg') }}" alt="Scroll down" class="w-16 md:w-14 animate-bounce">
      </a>
    </div>

    <!-- CARA RESERVASI -->
    <section id="reservasi" class="py-14 bg-[#ffffff]">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-semibold text-center mb-12">Cara Reservasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <!-- step 1 -->
          <div class="flex flex-col items-center">
            <div
              class="w-14 h-14 rounded-full bg-[#000000] flex items-center justify-center text-white font-semibold text-lg">
              1</div>
            <p class="mt-6 text-sm leading-relaxed max-w-xs">
              <span class="bg-yellow-200 text-yellow-800 font-medium px-1 rounded-sm"> Kamu tidak perlu login. </span>
              Kamu bisa
              langsung memilih jenis reservasi yang diinginkan.
            </p>
          </div>
          <!-- step 2 -->
          <div class="flex flex-col items-center">
            <div
              class="w-14 h-14 rounded-full bg-[#000000] flex items-center justify-center text-white font-semibold text-lg">
              2</div>
            <p class="mt-6 text-sm leading-relaxed max-w-xs">
              <span class="bg-yellow-200 text-yellow-800 font-medium px-1 rounded-sm"> Pilih Meja/Ruangan</span>
              Favoritmu. Selanjutnya, kamu akan diarahkan ke halaman menu. Silahkan, </br> <span
                class="bg-yellow-200 text-yellow-800 font-medium px-1 rounded-sm">pilih menu</span>
              kesukaanmu.
            </p>
          </div>
          <!-- step 3 -->
          <div class="flex flex-col items-center">
            <div
              class="w-14 h-14 rounded-full bg-[#000000] flex items-center justify-center text-white font-semibold text-lg">
              3</div>
            <p class="mt-6  text-sm leading-relaxed max-w-xs">
              <span class="bg-yellow-200 text-yellow-800 font-medium px-1 rounded-sm"> Selesaikan pembayaran
              </span>online
              dengan aman dan dapatkan konfirmasi reservasi secara instan.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="py-12 bg-[#dfe6da]">
      <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-3xl font-semibold text-[#414939] mb-4">Tentang Kami</h3>
          <p class="text-[#414939] leading-relaxed">
            🚩 Jl. Mawar, masuk dari Jl. Bangau sakti 50m arah SMK Farmasi Pekanbaru. <br />
            🕑 14:00 - 24:00 WIB. <br />
            ⚠️ VIP Room (meeting, pelatihan, dll) <br />
            ✔️ Mushalla, Parkir Luas <br />
            📳 WA Reservasi 0852-2183-6131 <br />
            👆Follow us on Instagram @homeycafe.pku <br />
        </div>
        <div>
          <img src="{{ asset('../images/cover.webp') }}" alt="Tampak depan Homey Cafe"
            class="rounded-lg shadow-md w-full object-cover">
        </div>
      </div>
    </section>

  </div>

  @include('layouts.partials.footer')
</body>

</html>