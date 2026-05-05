<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;
        
        sidebar.classList.toggle('collapsed');
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }

    // Inicialización del estado del sidebar para evitar saltos visuales
    (function() {
        // Ejecución inmediata si el sidebar ya está en el DOM (o esperar a DOMContentLoaded)
        const init = () => {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            sidebar.classList.add('is-initializing');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }

            requestAnimationFrame(() => {
                sidebar.classList.remove('is-initializing');
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
