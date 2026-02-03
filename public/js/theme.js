// Theme Initialization and Management
(function () {
    if (window.tailwind) {
        window.tailwind.config = window.tailwind.config || {};
        window.tailwind.config.darkMode = 'class';
        if (typeof window.tailwind.refresh === 'function') {
            window.tailwind.refresh();
        }
    }

    const storageKey = 'pinjam_qr_theme';

    function getStoredTheme() {
        const value = localStorage.getItem(storageKey);
        if (value === 'light' || value === 'dark') {
            return value;
        }
        if (value !== null) {
            localStorage.removeItem(storageKey);
        }
        return null;
    }

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    function resolveTheme() {
        const storedTheme = getStoredTheme();
        if (storedTheme) {
            return storedTheme;
        }
        return 'dark';
    }

    function syncToggleIcons() {
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        if (!themeToggleDarkIcon || !themeToggleLightIcon) {
            return;
        }

        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.add('hidden');
            themeToggleDarkIcon.classList.remove('hidden');
        } else {
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
        }
    }

    // Apply immediately to avoid flash
    applyTheme(resolveTheme());


    document.addEventListener('DOMContentLoaded', function () {
        const themeToggleBtn = document.getElementById('theme-toggle');
        if (!themeToggleBtn) {
            return;
        }

        syncToggleIcons();

        themeToggleBtn.addEventListener('click', function () {
            const nextTheme = document.documentElement.classList.contains('dark')
                ? 'light'
                : 'dark';

            applyTheme(nextTheme);
            localStorage.setItem(storageKey, nextTheme);
            syncToggleIcons();
        });
    });
})();
