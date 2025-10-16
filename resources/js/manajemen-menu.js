document.addEventListener('DOMContentLoaded', function () {

    const tableRows = document.querySelectorAll('#menuTable tbody tr');
    const searchInput = document.getElementById('searchInput');
    const entriesSelect = document.getElementById('entries');

    // --- FUNGSI GABUNGAN UNTUK FILTER & PAGINATION ---
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedValue = parseInt(entriesSelect.value, 10);
        let visibleCount = 0;

        tableRows.forEach(row => {
            const menuNameCell = row.querySelector('td:nth-child(2)');
            let matchesSearch = true; // Anggap cocok dulu

            if (menuNameCell) {
                const menuName = menuNameCell.textContent.toLowerCase();
                matchesSearch = menuName.includes(searchTerm);
            }

            if (matchesSearch && visibleCount < selectedValue) {
                row.style.display = ''; // Tampilkan baris jika cocok & masih dalam limit
                visibleCount++;
            } else {
                row.style.display = 'none'; // Sembunyikan jika tidak cocok atau sudah melebihi limit
            }
        });
    }

    if(searchInput) searchInput.addEventListener('keyup', applyFilters);
    if(entriesSelect) entriesSelect.addEventListener('change', applyFilters);

    // --- LOGIKA UNTUK TOGGLE DAN BADGE STATUS ---
    const statusToggles = document.querySelectorAll('#menuTable .toggle');
    const themeColors = {
        available: '#A9B89D',
        notAvailable: '#6B7280',
    };

    function updateAppearance(toggle) {
        const row = toggle.closest('tr');
        const badge = row.querySelector('.badge');
        
        badge.classList.remove('bg-green-200', 'text-green-800', 'bg-gray-200', 'text-gray-700');
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
        toggle.addEventListener('change', function() {
            updateAppearance(this);
        });
    });

    // --- FUNGSI MODAL "TAMBAH MENU" (YANG SEBELUMNYA HILANG) ---
    const tambahMenuBtn = document.getElementById('tambahMenuBtn');
    const modalTambah = document.getElementById('modal_tambah_menu');
    if (tambahMenuBtn && modalTambah) {
        tambahMenuBtn.addEventListener('click', () => {
            modalTambah.showModal();
        });
    }

    // --- FUNGSI MODAL "UBAH DETAIL" (YANG SEBELUMNYA HILANG) ---
    const ubahDetailButtons = document.querySelectorAll('.btn-ubah-detail');
    const modalUbah = document.getElementById('modal_ubah_detail');

    if (ubahDetailButtons && modalUbah) {
        const formUbahNama = document.getElementById('ubah_nama_menu');
        const formUbahHarga = document.getElementById('ubah_harga_menu');
        const formUbahKategori = document.getElementById('ubah_kategori_menu');
        
        ubahDetailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const nama = this.dataset.nama;
                const harga = this.dataset.harga;
                const kategori = this.dataset.kategori;

                formUbahNama.value = nama;
                formUbahHarga.value = harga;
                formUbahKategori.value = kategori;
                
                modalUbah.showModal();
            });
        });
    }

    // Panggil filter sekali saat halaman dimuat
    applyFilters();
});