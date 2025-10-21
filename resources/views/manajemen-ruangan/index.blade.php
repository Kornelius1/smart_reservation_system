<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Ruangan</title>
    @vite(['resources/css/app.css', 'resources/js/manajemen-ruangan.js'])

    {{-- CSS --}}
    <style>
        .toggle {
            --toggle-handle-color: white !important;
        }

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
                <li class="mb-4" title="Homey Cafe">
                    <div class="p-2 bg-brand-primary rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                    </div>
                </li>
                {{-- Tambahkan ikon sidebar lain sesuai kebutuhan --}}
                <li class="bg-brand-background rounded-lg" title="Manajemen Ruangan">
                    <a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 8.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 8.25 20.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25A2.25 2.25 0 0 1 13.5 8.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                    </a>
                </li>
            </ul>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="drawer-content flex flex-col items-center p-4 lg:p-8 ml-20">
            <div class="card w-full bg-white shadow-xl">
                <div class="card-body">
                    <h1 class="text-2xl font-bold text-brand-text border-b-4 border-brand-primary pb-2">MANAJEMEN RUANGAN</h1>
                    <div class="flex justify-start items-center my-4 space-x-4">
                        <div class="form-control relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input id="searchInput" type="text" placeholder="Cari berdasarkan nama ruangan..."
                                   class="input input-sm input-bordered w-72 pl-10" />
                        </div>
                    </div>

                    <div class="overflow-x-auto mt-4">
                        <table id="tableData" class="table w-full">
                            <thead>
                                <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                    <th>ID Ruangan</th>
                                    <th>Nama Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Lokasi</th>
                                    <th>Fasilitas</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-brand-text">
                                @forelse ($rooms ?? [] as $room)
                                    <tr class="text-center">
                                        <td>{{ $room['id_ruangan'] ?? $room->id_ruangan ?? '-' }}</td>
                                        <td>{{ $room['nama_ruangan'] ?? $room->nama_ruangan ?? '-' }}</td>
                                        <td>{{ $room['kapasitas'] ?? $room->kapasitas ?? '-' }} Orang</td>
                                        <td>{{ $room['lokasi'] ?? $room->lokasi ?? '-' }}</td>
                                        <td>{{ $room['fasilitas'] ?? $room->fasilitas ?? '-' }}</td>
                                        <td>{{ $room['keterangan'] ?? $room->keterangan ?? '-' }}</td>
                                        <td>
                                            @php
                                                $status = $room['status'] ?? $room->status ?? 0;
                                            @endphp
                                            <input type="checkbox" class="toggle toggle-md"
                                                   {{ $status ? 'checked' : '' }} />
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center">
                                                @php
                                                    $id = $room['id_ruangan'] ?? $room->id_ruangan ?? '';
                                                @endphp
                                                <a href="{{ route('manajemen-ruangan.edit', $id) }}"
                                                   class="btn btn-sm btn-primary">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Tidak ada data ruangan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>