document.addEventListener('DOMContentLoaded', function () {

    const tableRows = document.querySelectorAll('#menuTable tbody tr');
    const searchInput = document.getElementById('searchInput');
    const entriesSelect = document.getElementById('entries');

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedValue = parseInt(entriesSelect.value, 10);
        let visibleCount = 0;

        tableRows.forEach(row => {
            const menuNameCell = row.querySelector('td:nth-child(2)');
            let matchesSearch = true;

            if (menuNameCell) {
                const menuName = menuNameCell.textContent.toLowerCase();
                matchesSearch = menuName.includes(searchTerm);
            }

            if (matchesSearch && visibleCount < selectedValue) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    if(searchInput) searchInput.addEventListener('keyup', applyFilters);
    if(entriesSelect) entriesSelect.addEventListener('change', applyFilters);

    const statusToggles = document.querySelectorAll('#menuTable .toggle');
    const themeColors = {
        available: '#A9B89D',
        notAvailable: '#6B7280',
    };

    function updateAppearance(toggle) {
        const row = toggle.closest('tr');
        const badge = row.querySelector('.badge');
        
        badge.style.border = 'none';

        if (toggle.checked) {
            badge.textContent = 'Available';
            badge.style.backgroundColor = themeColors.available;
            badge.style.color = '#414939';
            toggle.style.backgroundColor = themeColors.available;
            toggle.style.borderColor = themeColors.available;
        } else {
            badge.textContent = 'Not Available';
            badge.style.backgroundColor = themeColors.notAvailable;
            badge.style.color = '#FFFFFF';
            toggle.style.backgroundColor = themeColors.notAvailable;
            toggle.style.borderColor = themeColors.notAvailable;
        }
    }

    statusToggles.forEach(updateAppearance);
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() { updateAppearance(this); });
    });

    // --- LOGIKA MODAL "TAMBAH MENU" ---
    const modalTambah = document.getElementById('modal_tambah_menu');
    const formTambah = document.getElementById('form_tambah_menu');
    const tambahMenuBtn = document.getElementById('tambahMenuBtn');

    if (tambahMenuBtn) {
        tambahMenuBtn.addEventListener('click', () => modalTambah.showModal());
    }
    
    if (formTambah) {
        formTambah.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const namaInput = document.getElementById('tambah_nama_menu');
            const hargaInput = document.getElementById('tambah_harga_menu');
            const kategoriInput = document.getElementById('tambah_kategori_menu');
            const gambarInput = document.getElementById('tambah_gambar_menu');

            const errorNama = document.getElementById('error_tambah_nama');
            const errorHarga = document.getElementById('error_tambah_harga');
            const errorKategori = document.getElementById('error_tambah_kategori');
            const errorGambar = document.getElementById('error_tambah_gambar');

            errorNama.textContent = '';
            errorHarga.textContent = '';
            errorKategori.textContent = '';
            errorGambar.textContent = '';

            let isValid = true;

            if (namaInput.value.trim() === '') { errorNama.textContent = 'Nama menu tidak boleh kosong.'; isValid = false; }
            if (hargaInput.value.trim() === '') { errorHarga.textContent = 'Harga tidak boleh kosong.'; isValid = false; }
            if (kategoriInput.value === '') { errorKategori.textContent = 'Kategori harus dipilih.'; isValid = false; }
            if (gambarInput.files.length === 0) { errorGambar.textContent = 'Gambar harus dipilih.'; isValid = false; }

            if (isValid) {
                console.log('Form Tambah Menu valid!');
                modalTambah.close();
                formTambah.reset();
            }
        });
    }

    // --- LOGIKA MODAL "UBAH DETAIL" ---
    const modalUbah = document.getElementById('modal_ubah_detail');
    const formUbah = document.getElementById('form_ubah_detail');
    const ubahDetailButtons = document.querySelectorAll('.btn-ubah-detail');

    if (ubahDetailButtons && modalUbah) {
        const formUbahNama = document.getElementById('ubah_nama_menu');
        const formUbahHarga = document.getElementById('ubah_harga_menu');
        const formUbahKategori = document.getElementById('ubah_kategori_menu');
        
        ubahDetailButtons.forEach(button => {
            button.addEventListener('click', function() {
                formUbahNama.value = this.dataset.nama;
                formUbahHarga.value = this.dataset.harga;
                formUbahKategori.value = this.dataset.kategori;
                modalUbah.showModal();
            });
        });
    }

    if (formUbah) {
        formUbah.addEventListener('submit', function(event) {
            event.preventDefault();
            // Anda bisa menambahkan validasi di sini juga jika perlu
            console.log('Form Ubah Detail valid!');
            modalUbah.close();
        });
    }

    applyFilters();
});