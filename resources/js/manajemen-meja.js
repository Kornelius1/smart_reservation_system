document.addEventListener('DOMContentLoaded', function () {
    // Bagian untuk status toggle telah dihapus

    // Logika pencarian (diperbarui)
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#tableData tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function (event) {
            
            // BARIS YANG DIUBAH: Tambahkan .trim()
            const searchTerm = event.target.value.toLowerCase().trim(); 
            
            tableRows.forEach(row => {
                const cell = row.querySelector('td:nth-child(3)'); // Kolom lokasi adalah td ke-3
                if (cell) {
                    
                    // BARIS YANG DIUBAH: Tambahkan .trim() untuk konsistensi
                    const cellText = cell.textContent.toLowerCase().trim(); 
                    
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