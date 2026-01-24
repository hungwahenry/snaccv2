export default function initTheme() {
    const html = document.documentElement;
    const theme = html.getAttribute('data-theme');

    function applySystemTheme(e) {
        if (theme === 'system') {
            if (e.matches) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }
    }

    if (theme === 'system') {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        applySystemTheme(mediaQuery);
        mediaQuery.addEventListener('change', applySystemTheme);
    }
}
