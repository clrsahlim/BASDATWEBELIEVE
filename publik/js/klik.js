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

function updateTime() {
    const timeDisplay = document.getElementById('timeDisplay');
    const now = new Date();

    // Format tanggal
    const day = now.getDate();
    const month = now.toLocaleString('en-US', { month: 'long' });
    const year = now.getFullYear();

    // Menambahkan "st", "nd", "rd", atau "th" pada tanggal
    const daySuffix = 
        day === 1 || day === 21 || day === 31 ? 'st' : 
        day === 2 || day === 22 ? 'nd' : 
        day === 3 || day === 23 ? 'rd' : 'th';

    // Format waktu
    const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
    const formattedTime = now.toLocaleTimeString('en-US', timeOptions);

    // Gabungkan hasil format
    timeDisplay.textContent = `${month} ${day}${daySuffix}, ${year} | ${formattedTime}`;
}

// Panggil pertama kali
updateTime();

// Update tiap detik untuk waktu
setInterval(updateTime, 1000);