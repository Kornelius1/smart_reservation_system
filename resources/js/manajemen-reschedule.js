  document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    // trim() ditambahkan di sini
                    const filter = searchInput.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('#tableData tbody tr');

                    rows.forEach(row => {
                        // trim() ditambahkan di sini
                        const rowText = (row.textContent || row.innerText).trim();

                        row.style.display = rowText.toLowerCase().includes(filter) ? '' : 'none';
                    });
                });
            }
        });