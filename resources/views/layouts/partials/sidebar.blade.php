<div class="drawer-side is-drawer-close:overflow-visible">
  <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>

  <div
    class="is-drawer-close:w-14 is-drawer-open:w-64 bg-base-200 flex flex-col items-start min-h-full transition-all duration-300">
    <ul class="menu w-full grow space-y-1">

      <li>
        <a href="/dashboard" class="is-drawer-close:tooltip is-drawer-close:tooltip-right text-[color:var(--base-content)] hover:text-primary" data-tip="Dashboard">
          @include('icons.dashboard')
          <span class="is-drawer-close:hidden">Dashboard</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-menu" class="is-drawer-close:tooltip is-drawer-close:tooltip-right text-[color:var(--base-content)] hover:text-primary"
          data-tip="Manajemen Menu">
          @include('icons.food')
          <span class="is-drawer-close:hidden">Manajemen Menu</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-meja" class="is-drawer-close:tooltip is-drawer-close:tooltip-right text-[color:var(--base-content)] hover:text-primary"
          data-tip="Manajemen Meja">
           @include('icons.table')
          <span class="is-drawer-close:hidden">Manajemen Meja</span>
        </a>
      </li>

      <li>
        <a href="{{ route('admin.manajemen-ruangan.index') }}"
          class="is-drawer-close:tooltip is-drawer-close:tooltip-right text-[color:var(--base-content)] hover:text-primary" data-tip="Manajemen Ruangan">
         @include('icons.room')
          <span class="is-drawer-close:hidden">Manajemen Ruangan</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-reservasi" class="is-drawer-close:tooltip is-drawer-close:tooltip-right text-[color:var(--base-content)] hover:text-primary"
          data-tip="Manajemen Reservasi">
          @include('icons.reservasi')
          <span class="is-drawer-close:hidden">Manajemen Reservasi</span>
        </a>
      </li>

      <li>
        <a href="/manajemen-reschedule" class="is-drawer-close:tooltip is-drawer-close:tooltip-right text-[color:var(--base-content)] hover:text-primary"
          data-tip="Manajemen Reschedule">
          @include('icons.reschedule')
          <span class="is-drawer-close:hidden">Manajemen Reschedule</span>
        </a>
      </li>

      <li>
        <a href="/laporan" class="is-drawer-close:tooltip is-drawer-close:tooltip-right text-[color:var(--base-content)] hover:text-primary" data-tip="Laporan">
          @include('icons.report')
          <span class="is-drawer-close:hidden">Manajemen Laporan</span>
        </a>
      </li>

      <li>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <button type="submit"
            class="is-drawer-close:tooltip is-drawer-close:tooltip-right w-full flex items-center text-left text-[color:var(--base-content)] hover:text-primary"
            data-tip="Logout">
            @include('icons.logout')
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