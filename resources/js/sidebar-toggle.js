document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('sidebar-toggle');
    const body = document.body;
    // Restore persisted state
    if (localStorage.getItem('sidebarMinimized') === 'true') {
        body.classList.add('sidebar-minimized');
    }
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('sidebar-minimized');
            localStorage.setItem('sidebarMinimized', body.classList.contains('sidebar-minimized'));
        });
    }
});
