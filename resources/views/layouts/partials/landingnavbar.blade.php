<nav class="navbar-gradient">
    <div class="navbar shadow-sm">
        <div class="flex-1">
            <a href="/" tabindex="0" class="btn btn-ghost">
                <img src="{{ asset('images/HOMEYY.png') }}" alt="Homey Logo" class="h-6" />
            </a>
        </div>

        <div class="flex-none">

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
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar h-4">
                                <div class="w-8 rounded-full">
                                    <img alt="Avatar pengguna"
                                        src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
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

            <div class="dropdown dropdown-end md:hidden">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content mt-4 z-50 p-2shadow bg-base-100 rounded-box w-52 text-black">

                    <li><a href="/">Home</a></li>
                    <li><a href="/pilih-reservasi">Reservasi</a></li>
                    <li><a href="/reschedule">Reschedule</a></li>
                    <li><a href="/#tentang">Tentang Kami</a></li>

                    @guest
                        <li><a href="{{ route('login') }}" class="font-bold">Login</a></li>
                    @endguest

                    @auth
                        <li>
                            <a href="{{ route('dashboard') }}" class="justify-between">
                                Dashboard
                                <span class="badge">New</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile"
                                style="display: none;">
                                @csrf
                            </form>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                Logout
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

        </div>
    </div>
</nav>