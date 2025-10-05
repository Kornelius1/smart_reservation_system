document.addEventListener('DOMContentLoaded', function () {

    // --- VARIABEL GLOBAL ---
    const tableRows = document.querySelectorAll('#menuTable tbody tr');

    // --- FUNGSI PENCARIAN ---
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function (event) {
            const searchTerm = event.target.value.toLowerCase();
            tableRows.forEach(row => {
                const menuNameCell = row.querySelector('td:nth-child(2)');
                if (menuNameCell) {
                    const menuName = menuNameCell.textContent.toLowerCase();
                    row.style.display = menuName.includes(searchTerm) ? '' : 'none';
                }
            });
        });
    }

    // --- FUNGSI TOGGLE STATUS ---
    const statusToggles = document.querySelectorAll('#menuTable .toggle');
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const row = this.closest('tr');
            const badge = row.querySelector('.badge');
            if (this.checked) {
                badge.textContent = 'Available';
                badge.classList.remove('bg-gray-200', 'text-gray-700');
                badge.classList.add('bg-green-200', 'text-green-800');
            } else {
                badge.textContent = 'Not Available';
                badge.classList.remove('bg-green-200', 'text-green-800');
                badge.classList.add('bg-gray-200', 'text-gray-700');
            }
        });
    });

    // --- FUNGSI MODAL "TAMBAH MENU" ---
    const tambahMenuBtn = document.getElementById('tambahMenuBtn');
    const modalTambah = document.getElementById('modal_tambah_menu');
    if (tambahMenuBtn && modalTambah) {
        tambahMenuBtn.addEventListener('click', () => {
            modalTambah.showModal();
        });
    }

    // --- LOGIKA BARU: PAGINATION "SHOW ENTRIES" ---
    const entriesSelect = document.getElementById('entries');
    
    function updatePagination() {
        const selectedValue = parseInt(entriesSelect.value, 10);
        tableRows.forEach((row, index) => {
            row.style.display = index < selectedValue ? '' : 'none';
        });
    }
    
    if (entriesSelect) {
        entriesSelect.addEventListener('change', updatePagination);
        // Panggil sekali saat halaman dimuat untuk menampilkan 10 entri pertama
        updatePagination();
    }
    
    // --- LOGIKA BARU: MODAL "UBAH DETAIL" ---
    const ubahDetailButtons = document.querySelectorAll('.btn-ubah-detail');
    const modalUbah = document.getElementById('modal_ubah_detail');

    if (ubahDetailButtons && modalUbah) {
        // Ambil semua elemen form di dalam modal ubah
        const formUbahNama = document.getElementById('ubah_nama_menu');
        const formUbahHarga = document.getElementById('ubah_harga_menu');
        const formUbahKategori = document.getElementById('ubah_kategori_menu');
        
        ubahDetailButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Ambil data dari atribut data-* tombol yang di-klik
                const nama = this.dataset.nama;
                const harga = this.dataset.harga;
                const kategori = this.dataset.kategori;

                // Isi form di dalam modal dengan data tersebut
                formUbahNama.value = nama;
                formUbahHarga.value = harga;
                formUbahKategori.value = kategori;
                
                // Tampilkan modal
                modalUbah.showModal();
            });
        });
    }
});