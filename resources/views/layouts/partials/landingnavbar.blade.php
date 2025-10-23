<nav class="navbar-gradient">
    <div class="navbar shadow-sm">
        <div class="flex-1">
            <a href="/" tabindex="0" class="btn btn-ghost">
                <img src="{{ asset('images/HOMEYY.png') }}" alt="Homey Logo" class="h-6" />
            </a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal">
                <li><a href="/">Home</a></li>
                <li><a href="/pilih-reservasi">Reservasi</a></li>
                <li><a href="/reschedule">Reschedule</a></li>
                <li><a href="/#tentang">Tentang Kami</a></li>
                <li> @guest
                    <li><a href="{{ route('login') }}" class="font-bold">Login</a></li>
                @endguest

                {{-- @guest
                <li><a href="{{ route('customer.landing.page') }}" class="font-bold">Login</a></li>
                @endguest --}}

                @auth
                    {{-- Tampil jika pengguna SUDAH login --}}
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </a>
                        </form>
                    </li>
                @endauth </li>
            </ul>
        </div>
    </div>
</nav>

