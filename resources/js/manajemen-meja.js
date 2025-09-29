document.addEventListener('DOMContentLoaded', function () { 
    const statusToggles = document.querySelectorAll('#tableData .toggle'); 
    
    // Definisikan warna yang sesuai dengan badge 
    const themeColors = { 
        available: '#A9B89D', 
        notAvailable: '#6B7280', 
        handle: 'white' 
    }; 
    
    // FUNGSI UNTUK MENGHILANGKAN CENTANG
    function removeCheckmarkStyles(toggle) {
        // Hilangkan appearance default
        toggle.style.appearance = 'none';
        toggle.style.webkitAppearance = 'none';
        toggle.style.mozAppearance = 'none';
        
        // Hilangkan background image (centang)
        toggle.style.backgroundImage = 'none';
        toggle.style.background = 'none';
        
        // Override semua pseudo elements
        const style = document.createElement('style');
        style.textContent = `
            .toggle::before, .toggle::after,
            .toggle:checked::before, .toggle:checked::after {
                display: none !important;
                content: "" !important;
                background: none !important;
            }
        `;
        if (!document.querySelector('style[data-toggle-fix]')) {
            style.setAttribute('data-toggle-fix', 'true');
            document.head.appendChild(style);
        }
    }
    
    // Fungsi untuk mengatur style badge dan toggle 
    function updateAppearance(toggle) { 
        const row = toggle.closest('tr'); 
        const badge = row.querySelector('.badge'); 
        
        // HILANGKAN CENTANG DULU
        removeCheckmarkStyles(toggle);
        
        // Hapus kelas CSS yang mungkin konflik 
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
            badge.style.color = 'white'; 
            
            toggle.style.backgroundColor = themeColors.notAvailable; 
            toggle.style.borderColor = themeColors.notAvailable; 
        } 
    } 
    
    // Atur style untuk semua toggle saat halaman dimuat 
    statusToggles.forEach(toggle => { 
        updateAppearance(toggle); 
    }); 
    
    // Event listener untuk toggle 
    statusToggles.forEach(toggle => { 
        toggle.addEventListener('change', function() { 
            updateAppearance(this); 
        }); 
    }); 
    
    // Logika pencarian 
    const searchInput = document.getElementById('searchInput'); 
    const tableRows = document.querySelectorAll('#tableData tbody tr'); 
    
    if (searchInput) { 
        searchInput.addEventListener('keyup', function (event) { 
            const searchTerm = event.target.value.toLowerCase(); 
            tableRows.forEach(row => { 
                const cell = row.querySelector('td:nth-child(2)'); 
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