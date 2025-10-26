@extends('layouts.admin')

@section('title', 'Manajemen Ruangan')

@push('styles')
    <style>
        .toggle {
            --toggle-handle-color: white !important;
        }

        .toggle:checked {
            background-image: none !important;
        }

        /* Sembunyikan panah di input number */
        input[type='number']::-webkit-outer-spin-button,
        input[type='number']::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type='number'] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@section('content')
    <div class="p-4 lg:p-8">
        <div class="flex items-center gap-3 mb-8">
            <button onclick="window.history.back()" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-2xl">Manajemen Ruangan</h1>
        </div>
        <div class="card w-full bg-white shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold border-b-4 border-brand-primary pb-2">MANAJEMEN RUANGAN</h1>

                {{-- Notifikasi Sukses / Error --}}
                @if (session('success'))
                    <div class="alert alert-success shadow-lg mt-4">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error shadow-lg mt-4">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif


                <div class="flex justify-start items-center my-4 space-x-4">
                    <div class="form-control relative my-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1.5 text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input id="searchInput" type="text" placeholder="Cari berdasarkan nama ruangan..."
                            class="input input-sm input-bordered w-72 pl-10" />
                    </div>
                    <button id="tambahRuanganBtn" class="btn btn-gradient">Tambah Ruangan</button>
                </div>


                <div class="overflow-x-auto mt-4">
                    <table id="tableData" class="table w-full">
                        <thead>
                            <tr class="text-brand-text text-center" style="background-color: #C6D2B9;">
                                <th>No</th>
                                <th>Nama Ruangan</th>
                                <th>Lokasi</th>
                                <th>Kapasitas</th>
                                <th>Minimum Order</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-brand-text">

                            @forelse ($rooms as $room)
                                <tr class="text-center">
                                    <th>{{ $loop->iteration }}</th>
                                    <td class="text-left">
                                        <div class="flex items-center space-x-5">
                                            <div class="avatar">
                                                <div class="mask mask-squircle w-12 h-12">
                                                    <img src="{{ asset($room->image_url) }}"
                                                        alt="{{ $room->nama_ruangan }}" class="w-full h-full object-cover" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $room->nama_ruangan }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $room->lokasi }}</td>
                                    <td>{{ $room->kapasitas }} Orang</td>
                                    <td>Rp {{ number_format($room->minimum_order, 0, ',', '.') }}</td>
                                    
                                    <td>
                                        {{-- 
                                          PERUBAHAN DI SINI:
                                          'admin.ruangan.updateStatus' -> 'admin.manajemen-ruangan.updateStatus'
                                        --}}
                                        <form action="{{ route('admin.manajemen-ruangan.updateStatus', $room->id) }}" method="POST" class="toggle-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $room->status == 'tersedia' ? 'tidak tersedia' : 'tersedia' }}">
                                            <input type="checkbox" class="toggle toggle-md"
                                                {{ $room->status == 'tersedia' ? 'checked' : '' }} 
                                                onchange="this.form.submit()"
                                            />
                                        </form>
                                    </td>

                                    <td>
                                        <div class="flex items-center justify-center space-x-2">
                                            {{-- 
                                              PERUBAHAN DI SINI:
                                              'admin.ruangan.update' -> 'admin.manajemen-ruangan.update'
                                            --}}
                                            <button
                                                class="btn btn-xs btn-ubah-detail"
                                                data-id="{{ $room->id }}"
                                                data-nama_ruangan="{{ $room->nama_ruangan }}"
                                                data-kapasitas="{{ $room->kapasitas }}"
                                                data-minimum_order="{{ $room->minimum_order }}"
                                                data-lokasi="{{ $room->lokasi }}"
                                                data-fasilitas="{{ $room->fasilitas }}"
                                                data-keterangan="{{ $room->keterangan }}"
                                                data-update_url="{{ route('admin.manajemen-ruangan.update', $room->id) }}"
                                                >Ubah Detail</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Tidak ada data ruangan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- =================================== --}}
    {{-- MODAL TAMBAH RUANGAN --}}
    {{-- =================================== --}}
    <dialog id="modal_tambah_ruangan" class="modal">
        <div class="modal-box bg-white">
            <h3 class="font-bold text-lg text-brand-text mb-4">Tambah Ruangan Baru</h3>
            
            {{-- 
              PERUBAHAN DI SINI:
              'admin.ruangan.store' -> 'admin.manajemen-ruangan.store'
            --}}
            <form id="form_tambah_ruangan" method="POST" action="{{ route('admin.manajemen-ruangan.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Nama Ruangan</span></label>
                    <input type="text" id="tambah_nama_ruangan" name="nama_ruangan" placeholder="Contoh: Ruang Meeting A" 
                           class="input input-bordered w-full" value="{{ old('nama_ruangan') }}" />
                    @error('nama_ruangan')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control w-full mb-2">
                        <label class="label"><span class="label-text text-brand-text">Kapasitas</span></label>
                        <input type="number" id="tambah_kapasitas" name="kapasitas" placeholder="Contoh: 10" 
                               class="input input-bordered w-full" value="{{ old('kapasitas') }}" />
                        @error('kapasitas')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                        @enderror
                    </div>
                    <div class="form-control w-full mb-2">
                        <label class="label"><span class="label-text text-brand-text">Minimum Order (Rp)</span></label>
                        <input type="number" id="tambah_minimum_order" name="minimum_order" placeholder="Contoh: 200000" 
                               class="input input-bordered w-full" value="{{ old('minimum_order') }}" />
                        @error('minimum_order')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                        @enderror
                    </div>
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Lokasi</span></label>
                    <input type="text" id="tambah_lokasi" name="lokasi" placeholder="Contoh: Lantai 2, Indoor" 
                           class="input input-bordered w-full" value="{{ old('lokasi') }}" />
                    @error('lokasi')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Fasilitas</span></label>
                    <textarea id="tambah_fasilitas" name="fasilitas" class="textarea textarea-bordered h-24" 
                              placeholder="Contoh: AC, Proyektor, Papan Tulis">{{ old('fasilitas') }}</textarea>
                    @error('fasilitas')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Keterangan</span></label>
                    <textarea id="tambah_keterangan" name="keterangan" class="textarea textarea-bordered h-24" 
                              placeholder="Deskripsi singkat ruangan">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text text-brand-text">Gambar Ruangan</span></label>
                    <input type="file" id="tambah_image_url" name="image_url" 
                           class="file-input file-input-bordered w-full" />
                    @error('image_url')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="modal_tambah_ruangan.close()">Batal</button>
                    <button type="submit" class="btn btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- =================================== --}}
    {{-- MODAL UBAH DETAIL --}}
    {{-- =================================== --}}
 {{-- =================================== --}}
    {{-- MODAL UBAH DETAIL (SUDAH DIPERBAIKI) --}}
    {{-- =================================== --}}
    <dialog id="modal_ubah_detail" class="modal">
        <div class="modal-box bg-white">
            <h3 class="font-bold text-lg text-brand-text mb-4">Ubah Detail Ruangan</h3>
            
            <form id="form_ubah_detail" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- 
                  TAMBAHAN PENTING:
                  Hidden input untuk melacak ID jika validasi gagal
                --}}
                <input type="hidden" name="update_room_id" id="ubah_room_id">

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Nama Ruangan</span></label>
                    {{-- TAMBAHKAN: name="nama_ruangan" --}}
                    <input type="text" id="ubah_nama_ruangan" name="nama_ruangan" class="input input-bordered w-full" />
                    {{-- 
                      PERBAIKAN: Tampilkan error spesifik untuk 'ubah'
                      Kita beri nama unik agar tidak bentrok dengan error 'tambah'
                    --}}
                    @error('nama_ruangan', 'update') 
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control w-full mb-2">
                        <label class="label"><span class="label-text text-brand-text">Kapasitas</span></label>
                        {{-- TAMBAHKAN: name="kapasitas" --}}
                        <input type="number" id="ubah_kapasitas" name="kapasitas" class="input input-bordered w-full" />
                        @error('kapasitas', 'update')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                        @enderror
                    </div>
                     <div class="form-control w-full mb-2">
                        <label class="label"><span class="label-text text-brand-text">Minimum Order (Rp)</span></label>
                        {{-- TAMBAHKAN: name="minimum_order" --}}
                        <input type="number" id="ubah_minimum_order" name="minimum_order" class="input input-bordered w-full" />
                        @error('minimum_order', 'update')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                        @enderror
                    </div>
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Lokasi</span></label>
                    {{-- TAMBAHKAN: name="lokasi" --}}
                    <input type="text" id="ubah_lokasi" name="lokasi" class="input input-bordered w-full" />
                    @error('lokasi', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Fasilitas</span></label>
                    {{-- TAMBAHKAN: name="fasilitas" --}}
                    <textarea id="ubah_fasilitas" name="fasilitas" class="textarea textarea-bordered h-24"></textarea>
                    @error('fasilitas', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-2">
                    <label class="label"><span class="label-text text-brand-text">Keterangan</span></label>
                    {{-- TAMBAHKAN: name="keterangan" --}}
                    <textarea id="ubah_keterangan" name="keterangan" class="textarea textarea-bordered h-24"></textarea>
                    @error('keterangan', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text text-brand-text">Ganti Gambar (Opsional)</span></label>
                    {{-- TAMBAHKAN: name="image_url" --}}
                    <input type="file" id="ubah_image_url" name="image_url" 
                           class="file-input file-input-bordered w-full" />
                    <label class="label"><span class="label-text-alt">Kosongkan jika tidak ingin ganti gambar</span></label>
                    @error('image_url', 'update')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="modal_ubah_detail.close()">Batal</button>
                    <button type="submit" class="btn btn-gradient bg-gradient-to-r from-brand-primary to-brand-primary-dark border-none">Update</button>
                </div>
            </form>
        </div>
    </dialog>

@endsection

@push('scripts')
    {{-- Skrip Pencarian --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const filter = searchInput.value.toLowerCase();
                    const rows = document.querySelectorAll('#tableData tbody tr');
                    rows.forEach(row => {
                        const roomNameCell = row.cells[1];
                        if (roomNameCell) {
                            const rowText = roomNameCell.textContent || roomNameCell.innerText;
                            row.style.display = rowText.toLowerCase().includes(filter) ? '' : 'none';
                        }
                    });
                });
            }

            // Skrip Modal
            const tambahBtn = document.getElementById('tambahRuanganBtn');
            const modalTambah = document.getElementById('modal_tambah_ruangan');
            if (tambahBtn) {
                tambahBtn.addEventListener('click', () => {
                    modalTambah.showModal();
                });
            }

            const modalUbah = document.getElementById('modal_ubah_detail');
            const formUbah = document.getElementById('form_ubah_detail');
            
            document.querySelectorAll('.btn-ubah-detail').forEach(button => {
                button.addEventListener('click', function () {
                    const updateUrl = this.dataset.update_url;

                    // Isi form modal ubah
                    formUbah.action = updateUrl;
                    document.getElementById('ubah_nama_ruangan').value = this.dataset.nama_ruangan;
                    document.getElementById('ubah_kapasitas').value = this.dataset.kapasitas;
                    document.getElementById('ubah_minimum_order').value = this.dataset.minimum_order;
                    document.getElementById('ubah_lokasi').value = this.dataset.lokasi;
                    document.getElementById('ubah_fasilitas').value = this.dataset.fasilitas;
                    document.getElementById('ubah_keterangan').value = this.dataset.keterangan;
                    
                    modalUbah.showModal();
                });
            });

            // Buka kembali modal jika ada error validasi
            @if($errors->any())
                @if($errors->has('nama_ruangan') || $errors->has('kapasitas') || $errors->has('minimum_order') || $errors->has('lokasi') || $errors->has('image_url'))
                    modalTambah.showModal();
                @endif
            @endif
        });
    </script>
@endpush