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
        'tersedia': 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800',
        'dipinjam': 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800'
    };
    return badges[ketersediaan] || 'bg-gray-100 text-gray-800';
}

/**
 * Dark Mode Logic
 */
function initDarkMode() {
    // Check local storage or system preference
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        updateToggleIcon('dark');
    } else {
        document.documentElement.classList.remove('dark');
        updateToggleIcon('light');
    }
}

function toggleDarkMode() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
        updateToggleIcon('light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
        updateToggleIcon('dark');
    }
}

function updateToggleIcon(mode) {
    const iconPath = document.getElementById('darkModeIcon');
    if (!iconPath) return;

    if (mode === 'dark') {
        // Sun icon for dark mode (to switch to light)
        iconPath.setAttribute('d', 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z');
    } else {
        // Moon icon for light mode (to switch to dark)
        iconPath.setAttribute('d', 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z');
    }
}

// Initialize on script load
initDarkMode();

/**
 * Logout function
 */
async function logout() {
    const result = await apiCall('logout.php', 'POST');
    if (result.success) {
        window.location.href = '/src/login.php';
    }
}
