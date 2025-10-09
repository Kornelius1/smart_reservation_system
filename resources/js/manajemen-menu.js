document.addEventListener('DOMContentLoaded', function () {

    // --- LOGIKA PENCARIAN ---
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#menuTable tbody tr');

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

    // --- FUNGSI MODAL "TAMBAH MENU" ---
    const tambahMenuBtn = document.getElementById('tambahMenuBtn');
    const modalTambah = document.getElementById('modal_tambah_menu');
    if (tambahMenuBtn && modalTambah) {
        tambahMenuBtn.addEventListener('click', () => {
            modalTambah.showModal();
        });
    }

    // --- LOGIKA PAGINATION "SHOW ENTRIES" ---
    // (Kode ini bisa kamu biarkan atau hapus jika tidak digunakan lagi)

    // --- LOGIKA BARU UNTUK TOGGLE DAN BADGE STATUS ---
    const statusToggles = document.querySelectorAll('#menuTable .toggle');
    const themeColors = {
        available: '#A9B89D', // Warna hijau untuk 'Available'
        notAvailable: '#6B7280', // Warna abu-abu untuk 'Not Available'
    };

    function updateAppearance(toggle) {
        const row = toggle.closest('tr');
        const badge = row.querySelector('.badge');
        
        // Hapus style lama agar tidak bentrok
        toggle.style.backgroundColor = '';
        toggle.style.borderColor = '';
        badge.style.backgroundColor = '';
        badge.style.color = '';

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

    // Terapkan style saat halaman pertama kali dimuat
    statusToggles.forEach(updateAppearance);

    // Tambahkan listener untuk setiap kali toggle diklik
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            updateAppearance(this);
        });
    });
});