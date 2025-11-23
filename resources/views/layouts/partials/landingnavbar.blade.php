<style>
    /* --- Warna premium emas gelap --- */
    :root {
        --gold-dark: #1F2937;
    }

    /* Text & icon gold */
    .mobile-menu-item {
        color: var(--gold-dark) !important;
        font-weight: 600;
    }

    .mobile-menu-item svg {
        stroke: #1F2937 !important;
        stroke-width: 2.1;
    }

    /* Hover */
    .mobile-menu-item:hover {
        color: var(--gold-dark) !important;
        background: rgba(255, 255, 255, 0.5);
    }

    .mobile-menu-glass {
        backdrop-filter: blur(18px);
        background: rgba(255, 255, 255, 0.28);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .navbar-floating {
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .mobile-menu-gradient {
        backdrop-filter: blur(6px);
        background: linear-gradient(135deg, #ffffff35, #d1d9e635);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }
</style>

<!-- NAVBAR UTAMA (TIDAK DIUBAH SAMA SEKALI) -->
<nav class="navbar-gradient navbar-floating">
    <div class="navbar shadow-sm">
        <div class="flex-1">
            <a href="/" tabindex="0" class="btn btn-ghost">
                <img src="{{ asset('images/HOMEYY.svg') }}" alt="Homey Logo" class="h-6" />
            </a>
        </div>

        <div class="flex-none">

            <!-- DESKTOP MENU (SAMA PERSIS) -->
            <ul class="menu menu-horizontal items-center hidden md:flex">
                <li><a href="/">Home</a></li>
                <li><a href="/pilih-reservasi">Reservasi</a></li>
                <li><a href="/reschedule">Reschedule</a></li>
                <li><a href="/#tentang">Tentang Kami</a></li>

                @guest
                    <li><a href="{{ route('login') }}" class="font-bold">Login</a></li>
                @endguest

                @auth
                    <li class="p-0">
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button"
                                class="avatar btn btn-ghost btn-square p-0 hover:bg-gray-100/30 transition-colors">
                                <div class="w-12 h-8 overflow-hidden rounded-md">
                                    <img src="{{ asset('images/avatar.svg') }}" alt="Avatar pengguna"
                                        class="object-cover w-full h-full" />
                                </div>
                            </div>
                            <ul tabindex="-1"
                                class="menu menu-sm dropdown-content bg-base-100 rounded-box mt-7 w-52 p-2 shadow text-black">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="justify-between">
                                        Dashboard
                                        <span class="badge">New</span>
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form-desktop"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endauth
            </ul>

            <!-- MOBILE BUTTON -->
            <button id="menuBtn" class="md:hidden btn btn-ghost btn-circle">
                <!-- Ikon burger original -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </div>
    </div>

    <!-- MOBILE DROPDOWN PREMIUM GOLD -->
    <div id="mobileDropdown" class="absolute top-full left-0 right-0 mx-3 mt-2 rounded-2xl shadow-xl mobile-menu-glass
               transition-all duration-300 opacity-0 -translate-y-3 pointer-events-none z-30">

        <ul class="menu p-3 space-y-1 text-[#B89B39]">

            <!-- HOME -->
            <li><a href="/" class="mobile-menu-item flex items-center gap-3">
                    @include('icons.home')
                    Home
                </a></li>

            <!-- RESERVASI -->
            <li><a href="/pilih-reservasi" class="mobile-menu-item flex items-center gap-3">
                    @include('icons.reservasi')
                    Reservasi
                </a></li>

            <!-- RESCHEDULE -->
            <li><a href="/reschedule" class="mobile-menu-item flex items-center gap-3">
                    @include('icons.reschedule')
                    Reschedule
                </a></li>

            <!-- TENTANG -->
            <li><a href="/#tentang" class="mobile-menu-item flex items-center gap-3">
                    @include('icons.info')
                    Tentang Kami
                </a></li>

            @guest
                <li>
                    <a href="{{ route('login') }}" class="mobile-menu-item flex items-center gap-3">
                        @include('icons.user')
                        Login
                    </a>
                </li>
            @endguest

            @auth
                <li>
                    <a href="{{ route('dashboard') }}" class="mobile-menu-item flex items-center gap-3">
                        @include('icons.dashboard')
                        Dashboard
                    </a>
                </li>

                <li>
                    <a onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                        class="mobile-menu-item flex items-center gap-3">
                        @include('icons.logout')
                        Logout
                    </a>
                </li>

                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile" class="hidden">
                    @csrf
                </form>
            @endauth

        </ul>

    </div>
</nav>

<script>
    const menuBtn = document.getElementById('menuBtn');
    const drop = document.getElementById('mobileDropdown');

    menuBtn.addEventListener('click', () => {
        const open = drop.classList.contains('opacity-100');

        if (open) {
            drop.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            drop.classList.add('opacity-0', '-translate-y-3', 'pointer-events-none');
        } else {
            drop.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            drop.classList.remove('opacity-0', '-translate-y-3', 'pointer-events-none');
        }
    });

    document.addEventListener('click', (e) => {
        if (!drop.contains(e.target) && !menuBtn.contains(e.target)) {
            drop.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            drop.classList.add('opacity-0', '-translate-y-3', 'pointer-events-none');
        }
    });
</script>