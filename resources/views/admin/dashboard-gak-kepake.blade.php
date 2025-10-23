{{-- yang gak kepake --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Menu Card -->
                <a href="" class="no-underline">
                    <div class="card bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white shadow-lg hover:shadow-2xl hover:scale-105 transition-all cursor-pointer h-full">
                        <div class="card-body items-center justify-center text-center">
                            <svg class="w-16 h-16 text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h2 class="card-title text-white text-lg">Manajemen Menu</h2>
                        </div>
                    </div>
                </a>

                <!-- Table Management Card -->
                <a href="" class="no-underline">
                    <div class="card bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white shadow-lg hover:shadow-2xl hover:scale-105 transition-all cursor-pointer h-full">
                        <div class="card-body items-center justify-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948-.684l1.498-4.493a1 1 0 011.502 0l1.498 4.493a1 1 0 00.948.684H19a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM3 15a2 2 0 012-2h3.28a1 1 0 00.948-.684l1.498-4.493a1 1 0 011.502 0l1.498 4.493a1 1 0 00.948.684H19a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2z" />
                            </svg>
                            <h2 class="card-title text-white text-lg">Manajemen Meja</h2>
                        </div>
                    </div>
                </a>

                <!-- Reservation Card -->
                <a href="" class="no-underline">
                    <div class="card bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white shadow-lg hover:shadow-2xl hover:scale-105 transition-all cursor-pointer h-full">
                        <div class="card-body items-center justify-center text-center">
                            <svg class="w-16 h-16 text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h2 class="card-title text-white text-lg">Manajemen Reservasi</h2>
                        </div>
                    </div>
                </a>

                <!-- Report Card -->
                <a href="" class="no-underline">
                    <div class="card bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white shadow-lg hover:shadow-2xl hover:scale-105 transition-all cursor-pointer h-full">
                        <div class="card-body items-center justify-center text-center">
                            <svg class="w-16 h-16 text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h2 class="card-title text-white text-lg">Laporan</h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
</x-app-layout>