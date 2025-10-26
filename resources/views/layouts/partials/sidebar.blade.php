<!-- Drawer Sidebar -->
<div class="drawer-side is-drawer-close:overflow-visible">
  <!-- Overlay untuk klik di luar -->
  <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>

  <!-- Kontainer Sidebar -->
  <div
    class="is-drawer-close:w-14 is-drawer-open:w-64 bg-base-200 flex flex-col items-start min-h-full transition-all duration-300">
    <!-- Navigasi utama -->
    <ul class="menu w-full grow space-y-1">
      <!-- Item Menu -->
      <li>
        <a href="/dashboard" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Dashboard">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
            <path
              d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
            </path>
          </svg>
          <span class="is-drawer-close:hidden">Dashboard</span>
        </a>
      </li>

      <!-- Gunakan struktur item yang sama -->
      <li>
        <a href="/manajemen-menu" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Menu">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M20 7h-9"></path>
            <path d="M14 17H5"></path>
            <circle cx="17" cy="17" r="3"></circle>
            <circle cx="7" cy="7" r="3"></circle>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Menu</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-meja" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Meja">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M20 7h-9"></path>
            <path d="M14 17H5"></path>
            <circle cx="17" cy="17" r="3"></circle>
            <circle cx="7" cy="7" r="3"></circle>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Meja</span>
        </a>
      </li>

      <li>
        <a href="{{ route('admin.manajemen-ruangan.index') }}"
          class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Manajemen Ruangan">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M20 7h-9"></path>
            <path d="M14 17H5"></path>
            <circle cx="17" cy="17" r="3"></circle>
            <circle cx="7" cy="7" r="3"></circle>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Ruangan</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-reservasi" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Reservasi">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M20 7h-9"></path>
            <path d="M14 17H5"></path>
            <circle cx="17" cy="17" r="3"></circle>
            <circle cx="7" cy="7" r="3"></circle>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Reservasi</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-reschedule" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Reschedule">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M20 7h-9"></path>
            <path d="M14 17H5"></path>
            <circle cx="17" cy="17" r="3"></circle>
            <circle cx="7" cy="7" r="3"></circle>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Reschedule</span>
        </a>
      </li>

      <li>
        <a href="/laporan" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Laporan">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M20 7h-9"></path>
            <path d="M14 17H5"></path>
            <circle cx="17" cy="17" r="3"></circle>
            <circle cx="7" cy="7" r="3"></circle>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Laporan</span>
        </a>
      </li>



      <!-- Tombol Logout -->
      {{-- <li>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <button type="submit"
            class="is-drawer-close:tooltip is-drawer-close:tooltip-right w-full flex items-center text-left"
            data-tip="Logout">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span class="is-drawer-close:hidden ml-2">Logout</span>
          </button>
        </form>
      </li> --}}
    </ul>

    <!-- Tombol buka/tutup drawer -->
    {{-- <div class="m-2 is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Open Sidebar">
      <label for="my-drawer-4"
        class="btn btn-ghost btn-circle drawer-button transition-transform duration-300 is-drawer-open:rotate-180">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" class="size-5">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <path d="M9 4v16"></path>
          <path d="M14 10l2 2-2 2"></path>
        </svg>
      </label>
    </div> --}}
  </div>
</div>