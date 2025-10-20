<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Reservasi</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @vite(['resources/css/app.css', 'resources/js/manajemen-reservasi.js', 'resources/css/manajemen.css'])
</head>

<body class="bg-brand-background">

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

        {{-- SIDEBAR --}}
    @include('layouts.partials.sidebar')
    
        {{-- KONTEN UTAMA --}}
        <div class="drawer-content flex flex-col items-center p-4 lg:p-8 ml-20">
            <div class="card w-full bg-white shadow-xl">
                <div class="card-body">
                    <h1 class="text-2xl brand-text-1 border-b-4 border-brand-primary pb-2">MANAJEMEN
                        RESERVASI</h1>
                    <div class="flex justify-start items-center my-4 space-x-4">
                        <div class="form-control relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1.2 " fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input id="searchInput" type="text" placeholder="Cari berdasarkan nama customer..."
                                class="input input-sm input-bordered w-72 pl-10" />
                        </div>
                    </div>


                    <div class="overflow-x-auto mt-4">

                        <table id="tableData" class="table w-full">
                            {{-- HEADER TABEL --}}
                            <thead>
                                <tr class="brand-text-1 text-center" style="background-color: #C6D2B9;">
                                    <th>ID Reservasi</th>
                                    <th>ID Transaksi</th>
                                    <th>Nomor Meja</th>
                                    <th>Nama Customer</th>
                                    <th>Nomor Telepon</th>
                                    <th>Jumlah Orang</th>
                                    <th>Tanggal</th>
                                    <th>Waktu Reservasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th> 
                                </tr>
                            </thead>
                            {{-- ISI TABEL --}}
                            <tbody class="text-brand-text">
                                @foreach ($reservations as $reservation)
                                    <tr class="text-center">
                                        <th>{{ $reservation['id_reservasi'] }}</th>
                                        <td>{{ $reservation['id_transaksi'] }}</td>
                                        <td>{{ $reservation['nomor_meja'] }}</td>
                                        <td>{{ $reservation['nama_customer'] }}</td>
                                        <td>{{ $reservation['nomor_telepon'] }}</td>
                                        <td>{{ $reservation['jumlah_orang'] }} Orang</td>
                                        <td>{{ $reservation['tanggal'] }}</td>
                                        <td>{{ $reservation['waktu_reservasi'] }}</td>
                                        <td>
                                            <span class="badge badge-sm" style="border: none;"></span>
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center">
                                                <input type="checkbox" class="toggle toggle-md"
                                                    {{ $reservation['status'] ? 'checked' : '' }} />
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
