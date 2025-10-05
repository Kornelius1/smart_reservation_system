<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu</title>
    @vite(['resources/css/app.css', 'resources/js/manajemen-menu.js'])
</head>
<body class="bg-brand-background">

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        
         {{-- SIDEBAR --}}
    @include('layouts.sidebar')
        
        <div class="drawer-content flex flex-col items-center p-4 lg:p-8 ml-20">
            <div class="card w-full bg-white shadow-xl">
                <div class="card-body">
                    <h1 class="text-2xl font-bold text-brand-text border-b-4 border-brand-primary pb-2">MANAJEMEN MENU</h1>
                    
                    <div class="flex justify-start items-center my-4 space-x-4">
                        <div class="form-control relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input id="searchInput" type="text" placeholder="Search..." class="input input-sm input-bordered w-72 pl-10" />
                        </div>
                        <button id="tambahMenuBtn" class="btn text-white bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Tambah Menu</button>
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
                        <table id="menuTable" class="table w-full">
                            <thead>
                                <tr class="text-brand-text text-center">
                                    <th>No</th>
                                    <th>Nama Menu</th>
                                    <th>Harga</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-brand-text">
                                @foreach ($menuItems as $item)
                                    <tr class="text-center">
                                        <th>{{ $loop->iteration }}</th>
                                        <td class="text-left">
                                            <div class="flex items-center space-x-5">
                                                <div class="avatar">
                                                    <div class="mask mask-squircle w-12 h-12">
                                                        <img src="{{ asset('images/menu/' . $item['foto']) }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover" />
                                                    </div>
                                                </div>
                                                <div><div class="font-bold">{{ $item['nama'] }}</div></div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                        <td>{{ $item['kategori'] }}</td>
                                        <td>
                                            @if ($item['tersedia'])
                                                <span class="badge badge-sm bg-green-200 text-green-800 border-none">Available</span>
                                            @else
                                                <span class="badge badge-sm bg-gray-200 text-gray-700 border-none">Not Available</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center space-x-2">
                                                <input type="checkbox" class="toggle toggle-sm toggle-natural" {{ $item['tersedia'] ? 'checked' : '' }} />
                                                <button 
                                                    class="btn btn-xs text-white bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none btn-ubah-detail"
                                                    data-nama="{{ $item['nama'] }}"
                                                    data-harga="{{ $item['harga'] }}"
                                                    data-kategori="{{ $item['kategori'] }}"
                                                    data-foto="{{ $item['foto'] }}">
                                                    Ubah Detail
                                                </button>
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

    <dialog id="modal_tambah_menu" class="modal">
        <div class="modal-box bg-white">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
            <h3 class="font-bold text-lg text-brand-text">Tambah Menu</h3>
            <div class="py-4">
                <div class="form-control w-full"><label class="label"><span class="label-text text-brand-text">Nama Menu</span></label><input type="text" placeholder="Masukkan Nama Menu" class="input input-bordered w-full" /></div>
                <div class="form-control w-full mt-4"><label class="label"><span class="label-text text-brand-text">Harga</span></label><label class="input-group"><span>Rp</span><input type="number" placeholder="15000" class="input input-bordered w-full" /></label></div>
                <div class="form-control w-full mt-4"><label class="label"><span class="label-text text-brand-text">Kategori</span></label><select class="select select-bordered w-full"><option disabled selected>Pilih Kategori</option><option>Snacks</option><option>Heavy Meal</option><option>Traditional</option><option>Juice</option><option>Fresh Drink</option><option>Special Taste</option><option>Ice Cream</option><option>Coffee</option></select></div>
                <div class="form-control w-full mt-4"><label class="label"><span class="label-text text-brand-text">Gambar</span></label><input type="file" class="file-input file-input-bordered w-full" /></div>
            </div>
            <div class="modal-action"><form method="dialog" class="w-full"><button class="btn w-full text-white bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Simpan</button></form></div>
        </div>
    </dialog>
    
    <dialog id="modal_ubah_detail" class="modal">
        <div class="modal-box bg-white">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
            <h3 class="font-bold text-lg text-brand-text">Ubah Detail</h3>
            <div class="py-4">
                <div class="form-control w-full"><label class="label"><span class="label-text text-brand-text">Nama Menu</span></label><input id="ubah_nama_menu" type="text" class="input input-bordered w-full" /></div>
                <div class="form-control w-full mt-4"><label class="label"><span class="label-text text-brand-text">Harga</span></label><label class="input-group"><span>Rp</span><input id="ubah_harga_menu" type="number" class="input input-bordered w-full" /></label></div>
                <div class="form-control w-full mt-4"><label class="label"><span class="label-text text-brand-text">Kategori</span></label><select id="ubah_kategori_menu" class="select select-bordered w-full"><option>Snacks</option><option>Heavy Meal</option><option>Traditional</option><option>Juice</option><option>Fresh Drink</option><option>Special Taste</option><option>Ice Cream</option><option>Coffee</option></select></div>
                <div class="form-control w-full mt-4"><label class="label"><span class="label-text text-brand-text">Gambar</span></label><input type="file" class="file-input file-input-bordered w-full" /></div>
            </div>
            <div class="modal-action"><form method="dialog" class="w-full"><button class="btn w-full text-white bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Simpan</button></form></div>
        </div>
    </dialog>

</body>
</html>