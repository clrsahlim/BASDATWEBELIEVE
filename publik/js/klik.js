document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('tombolSidebar');
    const links = sidebar.querySelectorAll('a');

    if (localStorage.getItem('sidebarVisible') === 'true') {
        sidebar.classList.remove('hidden');
    }
    
    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        localStorage.setItem('sidebarVisible', !sidebar.classList.contains('hidden'));
    });

    links.forEach(link => {
        link.addEventListener('click', () => {
            sidebar.classList.remove('hidden');
            localStorage.setItem('sidebarVisible', 'true');
        });
    });
});