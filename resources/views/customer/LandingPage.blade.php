<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Landing Page')</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  @vite(['resources/css/app.css', 'resources/css/component.css', 'resources/js/app.js'])
  {{-- @stack('styles') --}}
</head>

<body>
  @include('layouts.partials.landingnavbar')

  <div>
    <!-- HERO -->
    <section id="home" class="relative min-h-[70vh] flex items-center"
      style="background-image: url('{{ asset('../images/cover.jpg') }}'); background-size:cover; background-position:top; background-repeat:no-repeat">
      <div class="absolute inset-0 bg-black/35"></div>
      <div class="relative max-w-3xl px-6 py-20 text-white">
        <h1 class="text-4xl md:text-5xl font-serif font-semibold leading-tight mb-4">Momen Santai,<br />Meja Pasti Ada.
        </h1>
        <p class="text-base md:text-lg max-w-md mb-6">Cek ketersediaan meja secara real time dan amankan tempat
          favoritmu
          di Homey Cafe.</p>
        <a href="#reservasi"
          class="inline-block mt-2 px-6 py-3 bg-[#9CAF88] text-white rounded-md shadow hover:bg-[#819D85] transition">Reservasi
          Meja</a>
      </div>
    </section>

    <!-- CARA RESERVASI -->
    <section id="reservasi" class="py-16 bg-[#ffffff]">
      <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-semibold text-center mb-12 text-[#819D85]">Cara Reservasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <!-- step 1 -->
          <div class="flex flex-col items-center">
            <div
              class="w-14 h-14 rounded-full bg-[#9CAF88] flex items-center justify-center text-white font-semibold text-lg">
              1</div>
            <p class="mt-6 text-[#6E856F] text-sm leading-relaxed max-w-xs">
              Lihat denah kami, pilih meja favorit, dan tentukan jam kedatangan anda.
            </p>
          </div>
          <!-- step 2 -->
          <div class="flex flex-col items-center">
            <div
              class="w-14 h-14 rounded-full bg-[#9CAF88] flex items-center justify-center text-white font-semibold text-lg">
              2</div>
            <p class="mt-6 text-[#6E856F] text-sm leading-relaxed max-w-xs">
              Pilih menu favoritmu senilai min. Rp 30.000/orang untuk konfirmasi reservasi.
            </p>
          </div>
          <!-- step 3 -->
          <div class="flex flex-col items-center">
            <div
              class="w-14 h-14 rounded-full bg-[#9CAF88] flex items-center justify-center text-white font-semibold text-lg">
              3</div>
            <p class="mt-6 text-[#6E856F] text-sm leading-relaxed max-w-xs">
              Selesaikan pembayaran online dengan aman dan dapatkan konfirmasi reservasi secara instan.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="py-12">
      <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-3xl font-semibold text-[#819D85] mb-4">Tentang Kami</h3>
          <p class="text-[#6E856F] leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
        <div>
          <img src="{{ asset('../images/cover.jpg') }}" alt="Tampak depan Homey Cafe"
            class="rounded-lg shadow-md w-full object-cover">
        </div>
      </div>
    </section>

  </div>

  @include('layouts.partials.footer')
</body>

</html>