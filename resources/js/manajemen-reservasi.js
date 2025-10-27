// Menunggu hingga seluruh halaman (DOM) selesai dimuat
document.addEventListener('DOMContentLoaded', function () {
    
    // 1. Ambil elemen yang kita butuhkan
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('tableData');
    
    // Pastikan elemen tabel dan input-nya ada
    if (!searchInput || !table) {
        // Jika tidak ada, hentikan skrip untuk menghindari error
        return; 
    }

    const tableBody = table.getElementsByTagName('tbody')[0];
    const rows = tableBody.getElementsByTagName('tr');

    // ==========================================================
    // BAGIAN 1: Mencegah 'Enter' men-submit form (SANGAT PENTING)
    // ==========================================================
    searchInput.addEventListener('keydown', function (event) {
        // Cek jika tombol yang ditekan adalah 'Enter'
        if (event.key === 'Enter' || event.keyCode === 13) {
            // Mencegah aksi default browser (yaitu submit form pertama yang ditemukannya)
            event.preventDefault();
        }
    });

    // ==========================================================
    // BAGIAN 2: Fungsi untuk melakukan filter pencarian
    // ==========================================================
    searchInput.addEventListener('keyup', function () {
        const filterText = searchInput.value.toLowerCase();

        // Loop melalui setiap baris (tr) di dalam tbody
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            
            // Ambil sel (td) kelima, tempat "Nama Customer" berada
            // (Index 0 = ID, 1 = Transaksi, 2 = Meja, 3 = Ruangan, 4 = Nama)
            const nameCell = row.cells[4]; 

            if (nameCell) {
                // Ambil teks di dalam sel nama
                const cellText = nameCell.textContent || nameCell.innerText;

                // Cek apakah teks nama mengandung teks filter
                if (cellText.toLowerCase().includes(filterText)) {
                    // Jika cocok, tampilkan barisnya
                    row.style.display = '';
                } else {
                    // Jika tidak cocok, sembunyikan barisnya
                    row.style.display = 'none';
                }
            }
        }
    });

});