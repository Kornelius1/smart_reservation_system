<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Meja</title>
    @vite(['resources/css/app.css', 'resources/js/manajemen-meja.js'])

    {{-- CSS DIPAKSA DI SINI AGAR 100% BERHASIL --}}
    <style>
        /* Mengubah warna bulatan (handle) toggle menjadi PUTIH */
        .toggle {
            --toggle-handle-color: white !important; /* Ini memaksa bulatan jadi putih */
        }

        /* Menghilangkan ikon centang bawaan daisyUI */
        .toggle:checked {
            background-image: none !important;
        }
    </style>
</head>
<body class="bg-brand-background">

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        
        {{-- SIDEBAR --}}
        <div class="drawer-side" style="position: fixed;">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu p-4 w-20 min-h-full bg-white text-base-content items-center">
                <li class="mb-4" title="Homey Cafe"><div class="p-2 bg-brand-primary rounded-lg"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" /></svg></div></li>
                <li class="mt-2" title="Manajemen Menu"><a><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg></a></li>
                <li class="bg-brand-background rounded-lg" title="Manajemen Meja"><a><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 8.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 8.25 20.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25A2.25 2.25 0 0 1 13.5 8.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg></a></li>
                <li class="mt-2" title="Manajemen Reservasi"><a><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0h18M12 15.75h.008v.008H12v-.008Z" /></svg></a></li>
                <li class="mt-2" title="Laporan"><a><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg></a></li>
                <li class="w-full my-2 border-t border-gray-200"></li>
                <li title="Logout"><a><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg></a></li>
            </ul>
        </div>
        
        {{-- KONTEN UTAMA --}}
        <div class="drawer-content flex flex-col items-center p-4 lg:p-8 ml-20">
            <div class="card w-full bg-white shadow-xl">
                <div class="card-body">
                    <h1 class="text-2xl font-bold text-brand-text border-b-4 border-brand-primary pb-2">MANAJEMEN MEJA</h1>
                    
                    <div class="flex justify-start items-center my-4 space-x-4">
                        <div class="form-control relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1.2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input id="searchInput" type="text" placeholder="Cari berdasarkan lokasi..." class="input input-sm input-bordered w-72 pl-10" />
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 text-sm mb-4">
                        <span class="text-gray-600">Show</span>
                        <select id="entries" name="entries" class="select select-sm select-ghost w-20">
                            <option selected>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                        <span class="text-gray-600">entries</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="tableData" class="table w-full">
                            {{-- HEADER TABEL --}}
                            <thead>
                                <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                    <th>Nomor Meja</th>
                                    <th>Kapasitas</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            {{-- ISI TABEL --}}
                            <tbody class="text-brand-text">
                                @foreach ($tables as $table)
                                    <tr class="text-center">
                                        <th>{{ $table['nomor_meja'] }}</th>
                                        <td>{{ $table['kapasitas'] }} Orang</td>
                                        <td>{{ $table['lokasi'] }}</td>
                                        <td>
                                            <span class="badge badge-sm" style="border: none;"></span>
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center space-x-2">
                                                {{-- Toggle DIBESARKAN (md) dan dicek berdasarkan 'tersedia' --}}
                                                <input type="checkbox" class="toggle toggle-md" {{ $table['tersedia'] ? 'checked' : '' }} />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>