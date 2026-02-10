// Theme Initialization and Management
(function () {
    if (window.tailwind) {
        window.tailwind.config = window.tailwind.config || {};
        window.tailwind.config.darkMode = 'class';
    }

    const storageKey = 'pinjam_qr_theme';
    const html = document.documentElement;

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
        html.classList.toggle('dark', theme === 'dark');
        html.setAttribute('data-theme', theme);
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

        if (html.classList.contains('dark')) {
            // In dark mode, show sun icon as "switch to light"
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
        } else {
            // In light mode, show moon icon as "switch to dark"
            themeToggleLightIcon.classList.add('hidden');
            themeToggleDarkIcon.classList.remove('hidden');
        }
    }

    function hasTailwindUtilities() {
        if (!document.body) {
            return false;
        }

        const probe = document.createElement('div');
        probe.className = 'hidden';
        probe.style.position = 'absolute';
        probe.style.pointerEvents = 'none';
        probe.style.opacity = '0';
        document.body.appendChild(probe);

        const isAvailable = window.getComputedStyle(probe).display === 'none';
        probe.remove();
        return isAvailable;
    }

    function syncFallbackMode() {
        html.classList.toggle('theme-fallback', !hasTailwindUtilities());
    }

    // Apply immediately to avoid flash
    applyTheme(resolveTheme());


    document.addEventListener('DOMContentLoaded', function () {
        syncFallbackMode();

        const themeToggleBtn = document.getElementById('theme-toggle');
        if (!themeToggleBtn) {
            return;
        }

        syncToggleIcons();

        themeToggleBtn.addEventListener('click', function () {
            const nextTheme = html.classList.contains('dark')
                ? 'light'
                : 'dark';

            applyTheme(nextTheme);
            localStorage.setItem(storageKey, nextTheme);
            syncToggleIcons();
        });
    });

    window.addEventListener('load', syncFallbackMode);
})();
