<div class="drawer-side is-drawer-close:overflow-visible">
  <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>

  <div
    class="is-drawer-close:w-14 is-drawer-open:w-64 bg-base-200 flex flex-col items-start min-h-full transition-all duration-300">
    <ul class="menu w-full grow space-y-1">

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

      <li>
        <a href="/manajemen-menu" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Menu">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
            <path d="M8 7h6"></path>
            <path d="M8 11h6"></path>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Menu</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-meja" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Meja">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M21 9v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9"></path>
            <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v3"></path>
            <path d="M3 11h18"></path>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Meja</span>
        </a>
      </li>

      <li>
        <a href="{{ route('admin.manajemen-ruangan.index') }}"
          class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Manajemen Ruangan">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <rect width="18" height="18" x="3" y="3" rx="2"></rect>
            <path d="M3 12h18"></path>
            <path d="M12 3v18"></path>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Ruangan</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-reservasi" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Reservasi">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
            <line x1="16" x2="16" y1="2" y2="6"></line>
            <line x1="8" x2="8" y1="2" y2="6"></line>
            <line x1="3" x2="21" y1="10" y2="10"></line>
            <path d="m9 16 2 2 4-4"></path>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Reservasi</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-reschedule" class="is-drawer-close:tooltip is-drawer-close:tooltip-right"
          data-tip="Manajemen Reschedule">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
            <path d="M3 3v5h5"></path>
            <path d="M12 7v5l4 2"></path>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Reschedule</span>
        </a>
      </li>

      <li>
        <a href="/laporan" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Laporan">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="size-5">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg>
          <span class="is-drawer-close:hidden">Manajemen Laporan</span>
        </a>
      </li>

      <li>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <button type="submit"
            class="is-drawer-close:tooltip is-drawer-close:tooltip-right w-full flex items-center text-left"
            data-tip="Logout">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span class="is-drawer-close:hidden ml-2">Logout</span>
          </button>
        </form>
      </li>
    </ul>

    <div class="m-2 is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Open Sidebar">
      <label for="my-drawer-4" id="sidebar-pin-toggle"
        class="btn btn-ghost btn-circle drawer-button transition-transform duration-300 is-drawer-open:rotate-180">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" class="size-5">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <path d="M9 4v16"></path>
          <path d="M14 10l2 2-2 2"></path>
        </svg>
      </label>
    </div>
  </div>
</div>