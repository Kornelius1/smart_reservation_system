<nav class="navbar-gradient">
    <div class="navbar shadow-sm">
        <div class="flex-1">
            <a href="/" tabindex="0" class="btn btn-ghost">
                <img src="{{ asset('images/HOMEYY.png') }}" alt="Homey Logo" class="h-6" />
            </a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal items-center">
                <li><a href="/">Home</a></li>
                <li><a href="/pilih-reservasi">Reservasi</a></li>
                <li><a href="/reschedule">Reschedule</a></li>
                <li><a href="/#tentang">Tentang Kami</a></li>

                {{-- Tampil jika pengguna BELUM login --}}
                @guest
                    <li><a href="{{ route('login') }}" class="font-bold">Login</a></li>
                @endguest
            </ul>


            @auth
                <div class="dropdown dropdown-end ml-4">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img alt="Avatar pengguna"
                                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                        </div>
                    </div>
                    <ul tabindex="-1"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow text-black">
                        <li>
                            <a href="{{ route('dashboard') }}" class="justify-between">
                                Dashboard
                                <span class="badge">New</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                                @csrf
                            </form>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>