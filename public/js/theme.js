// Theme Initialization and Management
(function () {
    if (window.tailwind) {
        window.tailwind.config = window.tailwind.config || {};
        window.tailwind.config.darkMode = 'class';
    }

    const storageKey = 'pinjam_qr_theme';
    const cookieKey = 'pinjam_qr_theme';
    const sessionKey = 'pinjam_qr_theme';
    const html = document.documentElement;

    function safeReadLocalStorage(key) {
        try {
            return localStorage.getItem(key);
        } catch (e) {
            return null;
        }
    }

    function safeWriteLocalStorage(key, value) {
        try {
            localStorage.setItem(key, value);
            return true;
        } catch (e) {
            return false;
        }
    }

    function safeReadSessionStorage(key) {
        try {
            return sessionStorage.getItem(key);
        } catch (e) {
            return null;
        }
    }

    function safeWriteSessionStorage(key, value) {
        try {
            sessionStorage.setItem(key, value);
            return true;
        } catch (e) {
            return false;
        }
    }

    function readCookie(key) {
        const parts = document.cookie ? document.cookie.split('; ') : [];
        for (const part of parts) {
            const [name, ...rest] = part.split('=');
            if (name === key) {
                return decodeURIComponent(rest.join('='));
            }
        }
        return null;
    }

    function writeCookie(key, value) {
        const maxAge = 60 * 60 * 24 * 365; // 1 year
        document.cookie = `${key}=${encodeURIComponent(value)};path=/;max-age=${maxAge};SameSite=Lax`;
    }

    function normalizeTheme(value) {
        if (value === 'light' || value === 'dark') {
            return value;
        }
        return null;
    }

    function getStoredTheme() {
        const localValue = normalizeTheme(safeReadLocalStorage(storageKey));
        if (localValue) {
            return localValue;
        }

        const sessionValue = normalizeTheme(safeReadSessionStorage(sessionKey));
        if (sessionValue) {
            return sessionValue;
        }

        const cookieValue = normalizeTheme(readCookie(cookieKey));
        if (cookieValue) {
            return cookieValue;
        }

        return null;
    }

    function persistTheme(theme) {
        safeWriteLocalStorage(storageKey, theme);
        safeWriteSessionStorage(sessionKey, theme);
        writeCookie(cookieKey, theme);
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
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
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
            persistTheme(nextTheme);
            syncToggleIcons();
        });
    });

    window.addEventListener('load', syncFallbackMode);
})();
