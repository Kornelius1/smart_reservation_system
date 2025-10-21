<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Reschedule</title>
    @vite(['resources/css/app.css', 'resources/js/manajemen-reschedule.js'])

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
                {{-- Sidebar icons mirip Homey Cafe --}}
                <li class="mb-4" title="Homey Cafe">
                    <div class="p-2 bg-brand-primary rounded-lg">
                        {{-- icon home --}}
                    </div>
                </li>
                <li class="bg-brand-background rounded-lg" title="Manajemen Reschedule">
                    <a>
                        {{-- icon reschedule --}}
                    </a>
                </li>
            </ul>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="drawer-content flex flex-col items-center p-4 lg:p-8 ml-20">
            <div class="card w-full bg-white shadow-xl">
                <div class="card-body">
                    <h1 class="text-2xl font-bold text-brand-text border-b-4 border-brand-primary pb-2">
                        MANAJEMEN RESCHEDULE
                    </h1>

                    <div class="flex justify-start items-center my-4 space-x-4">
                        <div class="form-control relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none"
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
                            <thead>
                                <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                    <th>ID Reschedule</th>
                                    <th>ID Transaksi</th>
                                    <th>Nama Customer</th>
                                    <th>Tanggal Reschedule</th>
                                    <th>Waktu Reschedule</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-brand-text">
                                @foreach ($reschedules as $reschedule)
                                    <tr class="text-center">
                                        <td>{{ $reschedule->id_reschedule }}</td>
                                        <td>{{ $reschedule->id_transaksi }}</td>
                                        <td>{{ $reschedule->nama_customer }}</td>
                                        <td>{{ $reschedule->tanggal_reschedule }}</td>
                                        <td>{{ $reschedule->waktu_reschedule }}</td>
                                        <td>
                                            <span class="badge badge-sm {{ $reschedule->status == 'Pending' ? 'badge-warning' : ($reschedule->status == 'Disetujui' ? 'badge-success' : 'badge-error') }}">
                                                {{ $reschedule->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center">
                                                <button class="btn btn-xs btn-primary" onclick="changeStatus(this)">Ubah Status</button>
                                                <select class="select select-sm border rounded" onchange="changeAvailable(this)">
                                                    <option value="Available" {{ $reschedule->available ? 'selected' : '' }}>Available</option>
                                                    <option value="NonAvailable" {{ !$reschedule->available ? 'selected' : '' }}>Enggak</option>
                                                </select>
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

    <script>
        function changeStatus(btn) {
            const statusCell = btn.closest('tr').querySelector('td:nth-child(6) span');
            const currentStatus = statusCell.textContent;
            let newStatus = '';
            if(currentStatus === 'Pending') newStatus = 'Disetujui';
            else if(currentStatus === 'Disetujui') newStatus = 'Ditolak';
            else newStatus = 'Pending';

            statusCell.textContent = newStatus;
            statusCell.className = 'badge badge-sm ' + (newStatus === 'Pending' ? 'badge-warning' : newStatus === 'Disetujui' ? 'badge-success' : 'badge-error');
        }

        function changeAvailable(select) {
            const value = select.value;
            select.className = 'select select-sm border rounded ' + (value === 'Available' ? 'select-success' : 'select-error');
        }

        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#tableData tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>

</body>

</html>
