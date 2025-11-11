document.addEventListener('DOMContentLoaded', function () {
    
    // ==================================
    // Selektor Elemen
    // ==================================
    const searchInput = document.getElementById('searchInput');
    const modalTambah = document.getElementById('modal_tambah_ruangan');
    const modalUbah = document.getElementById('modal_ubah_detail');
    const formUbah = document.getElementById('form_ubah_detail');
    const modalHapus = document.getElementById('modal_hapus');
    const formHapus = document.getElementById('form_hapus');
    const hapusNamaSpan = document.getElementById('hapus_nama_ruangan');
    const tambahBtn = document.getElementById('tambahRuanganBtn');

    // Ambil status error dari data-attributes
    const tambahHasErrors = modalTambah && modalTambah.dataset.showOnError === 'true';
    const updateHasErrors = modalUbah && modalUbah.dataset.showOnError === 'true';

    // ==================================
    // SKRIP PENCARIAN
    // ==================================
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const filter = searchInput.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#tableData tbody tr');
            
            rows.forEach(row => {
                const roomNameCell = row.cells[1]; // Kolom ke-2 (index 1)
                if (roomNameCell) {
                    const rowText = (roomNameCell.textContent || roomNameCell.innerText).toLowerCase().trim();
                    row.style.display = rowText.includes(filter) ? '' : 'none';
                }
            });
        });
    }

    // ==================================
    // SKRIP MODAL
    // ==================================

    // --- Modal Tambah ---
    if (tambahBtn && modalTambah) {
        tambahBtn.addEventListener('click', () => {
            modalTambah.showModal();
        });
    }

    // --- Modal Ubah (Event Listener) ---
    document.querySelectorAll('.btn-ubah-detail').forEach(button => {
        button.addEventListener('click', function () {
            const updateUrl = this.dataset.update_url;
            const roomId = this.dataset.id; // Ambil ID

            // Isi form modal ubah
            if (formUbah) {
                formUbah.action = updateUrl;
            }
            if (document.getElementById('ubah_room_id')) {
                document.getElementById('ubah_room_id').value = roomId; // Set hidden ID
            }
            
            // =======================================================
            // PERUBAHAN DI SINI:
            // Kita hapus kondisi 'if (!updateHasErrors)'.
            // Kita ingin form SELALU diisi dengan data dari tombol
            // yang diklik, menimpa data 'old()' yang mungkin ada
            // (kecuali saat page load pertama kali, yang ditangani di bawah).
            // =======================================================
            document.getElementById('ubah_nama_ruangan').value = this.dataset.nama_ruangan;
            document.getElementById('ubah_kapasitas').value = this.dataset.kapasitas;
            document.getElementById('ubah_minimum_order').value = this.dataset.minimum_order;
            document.getElementById('ubah_lokasi').value = this.dataset.lokasi;
            document.getElementById('ubah_fasilitas').value = this.dataset.fasilitas;
            document.getElementById('ubah_keterangan').value = this.dataset.keterangan;

            if (modalUbah) {
                modalUbah.showModal();
            }
        });
    });

    // --- Modal Hapus ---
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function () {
            const deleteUrl = this.dataset.delete_url;
            const nama = this.dataset.nama;
            
            if (formHapus) {
                formHapus.action = deleteUrl;
            }
            if (hapusNamaSpan) {
                hapusNamaSpan.textContent = nama;
            }
            if (modalHapus) {
                modalHapus.showModal();
            }
        });
    });

    // ==================================
    // SKRIP VALIDASI ERROR (Versi .js)
    // (Bagian ini sudah benar dan tidak diubah)
    // ==================================
    
    // 1. Cek error untuk modal TAMBAH
    if (tambahHasErrors) {
        modalTambah.showModal();
    }

    // 2. Cek error untuk modal UBAH
    if (updateHasErrors) {
        const failedUpdateId = modalUbah.dataset.failedId;
        const baseUrl = modalUbah.dataset.baseUrl;
        
        if (failedUpdateId && baseUrl && formUbah) {
            formUbah.action = `${baseUrl}/${failedUpdateId}`;
            modalUbah.showModal();
        }
    }
});