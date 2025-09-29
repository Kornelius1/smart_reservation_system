// Menunggu sampai seluruh halaman HTML selesai dimuat sebelum menjalankan kode ini.
document.addEventListener('DOMContentLoaded', function () {

    // --- LOGIKA UNTUK FUNGSI PENCARIAN ---
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#menuTable tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function (event) {
            const searchTerm = event.target.value.toLowerCase();

            tableRows.forEach(row => {
                const menuNameCell = row.querySelector('td:nth-child(2)'); // Ambil sel nama menu
                if (menuNameCell) {
                    const menuName = menuNameCell.textContent.toLowerCase();
                    if (menuName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }

    // --- LOGIKA UNTUK TOGGLE STATUS ---
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

    // --- LOGIKA UNTUK MEMBUKA MODAL "TAMBAH MENU" ---
    const tambahMenuBtn = document.getElementById('tambahMenuBtn');
    const modal = document.getElementById('modal_tambah_menu');

    if (tambahMenuBtn && modal) {
        tambahMenuBtn.addEventListener('click', () => {
            modal.showModal(); // Perintah DaisyUI untuk menampilkan modal
        });
    }

});