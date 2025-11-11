// Ambil semua elemen yang diperlukan
const tableRows = document.querySelectorAll('#tableData tbody tr');
const searchInput = document.getElementById('searchInput');
const entriesSelect = document.getElementById('entries');

/**
 * Fungsi ini menerapkan filter pencarian DAN filter jumlah entri
 * secara bersamaan.
 */
function applyFilters() {
    // 1. Ambil nilai dari kedua input
    const searchTerm = searchInput.value.toLowerCase().trim();
    const selectedValue = parseInt(entriesSelect.value, 10);
    
    // 2. Siapkan counter untuk baris yang terlihat
    let visibleCount = 0;

    tableRows.forEach(row => {
        // 3. Logika Pencarian (Search)
        // Kita cari berdasarkan kolom 'Lokasi' (td ke-3)
        const cell = row.querySelector('td:nth-child(3)'); 
        let matchesSearch = true; // Asumsikan cocok jika sel tidak ditemukan

        if (cell) {
            const cellText = cell.textContent.toLowerCase().trim();
            matchesSearch = cellText.includes(searchTerm);
        }

        // 4. Logika Tampilkan/Sembunyikan (Gabungan)
        // Tampilkan baris HANYA JIKA:
        // - Cocok dengan pencarian (matchesSearch)
        // - DAN Jumlah baris yang sudah terlihat (visibleCount) masih di bawah batas (selectedValue)
        if (matchesSearch && visibleCount < selectedValue) {
            row.style.display = ''; // Tampilkan baris
            visibleCount++;
        } else {
            row.style.display = 'none'; // Sembunyikan baris
        }
    });
}

// Pasang listener ke kedua elemen filter
if (searchInput) {
    searchInput.addEventListener('keyup', applyFilters);
}
if (entriesSelect) {
    entriesSelect.addEventListener('change', applyFilters);
}

// Jalankan filter saat halaman pertama kali dimuat
applyFilters();