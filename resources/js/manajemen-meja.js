<<<<<<< HEAD
// Global variables
let currentEditId = null;
let searchTimeout = null;

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    setupCSRFToken();
});

// Initialize all event listeners
function initializeEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('search-meja');
    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }

    

    // Setup table event listeners
    setupTableEventListeners();

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.matches('[id^="modal-"]')) {
            closeModal(e.target.id);
        }
    });
}

// Setup CSRF token for fetch requests
function setupCSRFToken() {
    window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}



    // Toggle status change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('toggle-status')) {
            const id = e.target.getAttribute('data-id');
            handleToggleStatus(id, e.target);
        }
    });


// Search functionality with debouncing
function handleSearch(e) {
    const searchTerm = e.target.value.trim();
    
    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    
    // Set new timeout for debouncing
    searchTimeout = setTimeout(function() {
        searchMeja(searchTerm);
    }, 300);
}

// Perform search request
function searchMeja(searchTerm) {
    showLoading();
    
    fetch(`/manajemen-meja/search?search=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
        },
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            document.getElementById('table-body').innerHTML = data.html;
        } else {
            showToast('Gagal melakukan pencarian', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Search error:', error);
        showToast('Terjadi kesalahan saat mencari data', 'error');
    });
}

// Modal functions
function openTambahModal() {
    currentEditId = null;
    resetForm();
    document.getElementById('modal-title').textContent = 'Tambah Meja';
    showModal('modal-form-meja');
}

function openEditModal(id) {
    currentEditId = id;
    showLoading();
    
    fetch(`/manajemen-meja/${id}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
        },
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            const meja = data.data;
            document.getElementById('modal-title').textContent = 'Edit Meja';
            document.getElementById('meja-id').value = meja.id;
            document.getElementById('nomor-meja').value = meja.nomor_meja;
            document.getElementById('kapasitas').value = meja.kapasitas;
            document.getElementById('lokasi').value = meja.lokasi;
            
            showModal('modal-form-meja');
        } else {
            showToast('Gagal mengambil data meja', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Edit fetch error:', error);
        showToast('Terjadi kesalahan saat mengambil data', 'error');
    });
}

function openDeleteModal(id) {
    currentEditId = id;
    showModal('modal-konfirmasi-hapus');
}

function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Handle form submission
function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    // Clear previous errors
    clearErrors();
    
    // Show loading
    const btnSubmit = document.getElementById('btn-submit');
    const btnLoading = document.getElementById('btn-loading');
    btnSubmit.disabled = true;
    btnLoading.classList.remove('hidden');
    
    const url = currentEditId 
        ? `/manajemen-meja/${currentEditId}` 
        : '/manajemen-meja';
    
    const method = currentEditId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'X-HTTP-Method-Override': method,
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        btnSubmit.disabled = false;
        btnLoading.classList.add('hidden');
        
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('modal-form-meja');
            refreshTable();
        } else {
            if (data.errors) {
                displayErrors(data.errors);
            } else {
                showToast(data.message || 'Terjadi kesalahan', 'error');
            }
        }
    })
    .catch(error => {
        btnSubmit.disabled = false;
        btnLoading.classList.add('hidden');
        console.error('Form submit error:', error);
        showToast('Terjadi kesalahan saat menyimpan data', 'error');
    });
}

// Handle confirm delete
function handleConfirmDelete() {
    if (!currentEditId) return;
    
    const btnKonfirmasi = document.getElementById('btn-konfirmasi-hapus');
    const hapusLoading = document.getElementById('hapus-loading');
    
    btnKonfirmasi.disabled = true;
    hapusLoading.classList.remove('hidden');
    
    fetch(`/manajemen-meja/${currentEditId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
        },
    })
    .then(response => response.json())
    .then(data => {
        btnKonfirmasi.disabled = false;
        hapusLoading.classList.add('hidden');
        
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('modal-konfirmasi-hapus');
            refreshTable();
        } else {
            showToast(data.message || 'Gagal menghapus meja', 'error');
        }
    })
    .catch(error => {
        btnKonfirmasi.disabled = false;
        hapusLoading.classList.add('hidden');
        console.error('Delete error:', error);
        showToast('Terjadi kesalahan saat menghapus data', 'error');
    });
}

// Handle toggle status
function handleToggleStatus(id, toggleElement) {
    const originalState = toggleElement.checked;
    
    fetch(`/manajemen-meja/${id}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Update badge in the same row
            const row = toggleElement.closest('tr');
            const badge = row.querySelector('span[class*="inline-flex"]');
            if (data.status_aktif) {
                badge.className = 'inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full';
                badge.textContent = 'Available';
            } else {
                badge.className = 'inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full';
                badge.textContent = 'Not Available';
            }
        } else {
            // Revert toggle state
            toggleElement.checked = !originalState;
            showToast(data.message || 'Gagal mengubah status', 'error');
        }
    })
    .catch(error => {
        // Revert toggle state
        toggleElement.checked = !originalState;
        console.error('Toggle status error:', error);
        showToast('Terjadi kesalahan saat mengubah status', 'error');
    });
}

// Dropdown functions
function toggleDropdown(id) {
    closeAllDropdowns();
    const dropdown = document.getElementById(`dropdown-${id}`);
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

function closeAllDropdowns() {
    const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
    dropdowns.forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('[onclick*="toggleDropdown"]') && !e.target.closest('[id^="dropdown-"]')) {
        closeAllDropdowns();
    }
});

// Utility functions
function refreshTable() {
    const searchValue = document.getElementById('search-meja').value;
    if (searchValue) {
        searchMeja(searchValue);
    } else {
        location.reload();
    }
}

function resetForm() {
    document.getElementById('form-meja').reset();
    document.getElementById('meja-id').value = '';
    clearErrors();
}

function clearErrors() {
    const errorElements = document.querySelectorAll('[id^="error-"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
    
    const inputElements = document.querySelectorAll('.border-red-500');
    inputElements.forEach(element => {
        element.classList.remove('border-red-500');
        element.classList.add('border-gray-300');
    });
}

function displayErrors(errors) {
    Object.keys(errors).forEach(field => {
        const errorElement = document.getElementById(`error-${field.replace('_', '-')}`);
        const inputElement = document.getElementById(field.replace('_', '-'));
        
        if (errorElement && inputElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove('hidden');
            inputElement.classList.remove('border-gray-300');
            inputElement.classList.add('border-red-500');
        }
    });
}

function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const colors = {
        success: 'bg-green-100 border-green-400 text-green-700',
        error: 'bg-red-100 border-red-400 text-red-700',
        warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
        info: 'bg-blue-100 border-blue-400 text-blue-700'
    };
    
    const icons = {
        success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
        error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
        warning: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
        info: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
    };
    
    const toastHTML = `
        <div id="${toastId}" class="border-l-4 p-4 mb-3 rounded-md shadow-md ${colors[type]} animate-slide-up">
            <div class="flex items-center">
                <div class="mr-3">${icons[type]}</div>
                <span>${message}</span>
                <button onclick="document.getElementById('${toastId}').remove()" class="ml-auto text-lg font-semibold">&times;</button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const toastElement = document.getElementById(toastId);
        if (toastElement) {
            toastElement.remove();
        }
    }, 5000);
}

function showLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
    }
}

function hideLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.classList.add('hidden');
        loadingOverlay.classList.remove('flex');
    }
}
=======
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
>>>>>>> ManajemenMeja1
