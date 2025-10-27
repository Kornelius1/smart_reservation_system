// --- BAGIAN FILTERING ---
const tableRows = document.querySelectorAll('#menuTable tbody tr');
const searchInput = document.getElementById('searchInput');
const entriesSelect = document.getElementById('entries');

function applyFilters() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedValue = parseInt(entriesSelect.value, 10);
    let visibleCount = 0;

    tableRows.forEach(row => {
        // Mengambil sel Nama Menu (kolom ke-2)
        const menuNameCell = row.querySelector('td:nth-child(2)'); 
        let matchesSearch = true;

        if (menuNameCell) {
            const menuName = menuNameCell.textContent.toLowerCase();
            matchesSearch = menuName.includes(searchTerm);
        }

        // Tampilkan baris jika cocok DAN masih dalam limit 'entries'
        if (matchesSearch && visibleCount < selectedValue) {
            row.style.display = ''; // Tampilkan baris
            visibleCount++;
        } else {
            row.style.display = 'none'; // Sembunyikan baris
        }
    });
}

// Pasang listener
if(searchInput) searchInput.addEventListener('keyup', applyFilters);
if(entriesSelect) entriesSelect.addEventListener('change', applyFilters);

// Jalankan filter saat halaman dimuat
applyFilters();