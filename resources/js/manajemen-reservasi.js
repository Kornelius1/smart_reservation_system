document.addEventListener('DOMContentLoaded', function () {
    const themeColors = {
        available: '#A9B89D', // hijau muda untuk Available
        notAvailable: '#6B7280', // abu untuk Not Available
    };

    // Ambil semua baris data
    const tableRows = document.querySelectorAll('#tableData tbody tr');

    tableRows.forEach(row => {
        const toggle = row.querySelector('.toggle');
        const badge = row.querySelector('.badge');

        if (!toggle || !badge) return; // jika ada baris tanpa toggle/badge

     
        toggle.classList.remove('toggle-success');

        function updateAppearance() {
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

        // Jalankan awal dan saat toggle berubah
        updateAppearance();
        toggle.addEventListener('change', updateAppearance);
    });

    // 🔍 Fungsi pencarian tetap
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function (event) {
            const searchTerm = event.target.value.toLowerCase();
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
