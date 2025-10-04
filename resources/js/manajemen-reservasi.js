document.addEventListener('DOMContentLoaded', function () {
    // Cari semua toggle di dalam kolom Aksi
    const statusToggles = document.querySelectorAll('#tableData td:nth-child(10) .toggle');

    const themeColors = {
        available: '#A9B89D', // Warna hijau untuk 'Available'
        notAvailable: '#6B7280', // Warna abu-abu untuk 'Not Available'
    };

    function updateAppearance(toggle) {
        const row = toggle.closest('tr');
        // Badge sekarang ada di kolom ke-9 (Status)
        const badge = row.querySelector('td:nth-child(9) .badge');

        // Hapus kelas CSS bawaan daisyUI agar tidak bentrok
        toggle.classList.remove('toggle-success');

        if (toggle.checked) {
            badge.textContent = 'Available';
            badge.style.backgroundColor = themeColors.available;
            badge.style.color = '#414939'; // Warna teks untuk status available

            toggle.style.backgroundColor = themeColors.available;
            toggle.style.borderColor = themeColors.available;
        } else {
            badge.textContent = 'Not Available';
            badge.style.backgroundColor = themeColors.notAvailable;
            badge.style.color = '#FFFFFF'; // Warna teks untuk status not available

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

    // Logika pencarian (tidak berubah)
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#tableData tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function (event) {
            const searchTerm = event.target.value.toLowerCase();
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});