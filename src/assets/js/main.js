/**
 * Main JavaScript Utilities
 * Helper functions untuk AJAX calls dan utilities
 */

const API_BASE = '/src/api';

/**
 * Make API call
 */
async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        }
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(`${API_BASE}/${endpoint}`, options);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, message: 'Terjadi kesalahan koneksi' };
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    const bgColor = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'info': 'bg-blue-500',
        'warning': 'bg-yellow-500'
    }[type] || 'bg-gray-500';
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Format tanggal Indonesia
 */
function formatTanggal(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('id-ID', options);
}

/**
 * Get badge color for kondisi
 */
function getKondisiBadge(kondisi) {
    const badges = {
        'baik': 'bg-green-100 text-green-800',
        'rusak ringan': 'bg-yellow-100 text-yellow-800',
        'rusak berat': 'bg-red-100 text-red-800'
    };
    return badges[kondisi] || 'bg-gray-100 text-gray-800';
}

/**
 * Get badge color for ketersediaan
 */
function getKetersediaanBadge(ketersediaan) {
    const badges = {
        'tersedia': 'bg-green-100 text-green-800',
        'dipinjam': 'bg-red-100 text-red-800'
    };
    return badges[ketersediaan] || 'bg-gray-100 text-gray-800';
}

/**
 * Logout function
 */
async function logout() {
    const result = await apiCall('logout.php', 'POST');
    if (result.success) {
        window.location.href = '/src/login.php';
    }
}
