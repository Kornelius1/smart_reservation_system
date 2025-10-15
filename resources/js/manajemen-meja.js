document.addEventListener('DOMContentLoaded', function () {
    const statusToggles = document.querySelectorAll('#tableData .toggle');

    const themeColors = {
        available: '#A9B89D',
        notAvailable: '#6B7280',
    };

    function updateAppearance(toggle) {
        const row = toggle.closest('tr');
        // Cari badge di kolom ke-4 (td ke-4)
        const badge = row.querySelector('td:nth-child(4) .badge');

        // Hapus kelas CSS bawaan daisyUI agar tidak bentrok
        toggle.classList.remove('toggle-success');

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

    // Jalankan fungsi untuk setiap toggle saat halaman pertama kali dimuat
    statusToggles.forEach(toggle => {
        updateAppearance(toggle);
    });

    // Tambahkan listener untuk setiap kali toggle diklik
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            updateAppearance(this);
        });
    });

    // Logika pencarian (tidak diubah)
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#tableData tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function (event) {
            const searchTerm = event.target.value.toLowerCase();
            tableRows.forEach(row => {
                const cell = row.querySelector('td:nth-child(3)'); // Kolom lokasi adalah td ke-3
                if (cell) {
                    const cellText = cell.textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }
});
