<nav class="navbar-gradient">
    <div class="navbar shadow-sm">
        <div class="flex-1">
            <a href="/" tabindex="0" class="btn btn-ghost">
                <img src="{{ asset('images/HOMEYY.png') }}" alt="Homey Logo" class="h-6" />
            </a>
        </div>

        <div class="flex-none">

            <ul class="menu menu-horizontal hidden md:flex">
                <li><a href="/">Home</a></li>
                <li><a href="/pilih-reservasi">Reservasi</a></li>
                <li><a href="/reschedule">Reschedule</a></li>
                <li><a href="/#tentang">Tentang Kami</a></li>
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
                    class="menu menu-sm dropdown-content mt-3 z-50 p-2 shadow bg-base-100 rounded-box w-52 text-black">
                    <li><a href="/">Home</a></li>
                    <li><a href="/pilih-reservasi">Reservasi</a></li>
                    <li><a href="/reschedule">Reschedule</a></li>
                    <li><a href="/#tentang">Tentang Kami</a></li>
                </ul>
            </div>

        </div>
    </div>
</nav>