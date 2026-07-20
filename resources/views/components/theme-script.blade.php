<script>
    (() => {
        const storageKey = 'storex-theme';
        const root = document.documentElement;
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)');

        const getTheme = () => {
            const savedTheme = localStorage.getItem(storageKey);

            return savedTheme ?? (systemPrefersDark.matches ? 'dark' : 'light');
        };

        const applyTheme = (theme) => {
            const isDark = theme === 'dark';

            root.classList.toggle('dark', isDark);
            root.style.colorScheme = isDark ? 'dark' : 'light';

            document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                button.setAttribute('aria-pressed', String(isDark));
                button.setAttribute('aria-label', isDark ? 'Ativar tema claro' : 'Ativar tema escuro');
                button.setAttribute('title', isDark ? 'Ativar tema claro' : 'Ativar tema escuro');
            });
        };

        window.storeXTheme = {
            toggle() {
                const nextTheme = root.classList.contains('dark') ? 'light' : 'dark';

                localStorage.setItem(storageKey, nextTheme);
                applyTheme(nextTheme);
            },
            apply: applyTheme,
        };

        applyTheme(getTheme());

        document.addEventListener('DOMContentLoaded', () => applyTheme(getTheme()));

        window.addEventListener('storage', (event) => {
            if (event.key === storageKey) {
                applyTheme(getTheme());
            }
        });
    })();
</script>
