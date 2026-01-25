const profileBtn = document.getElementById('profileBtn');
const dropdown = document.getElementById('myDropdown');

// 1. Fungsi klik untuk toggle (buka/tutup)
profileBtn.addEventListener('click', function(event) {
    event.stopPropagation(); // Mencegah event bubbling
    dropdown.classList.toggle('show');
});

// 2. Fungsi untuk menutup dropdown jika klik di luar area profil
window.addEventListener('click', function(event) {
    if (!profileBtn.contains(event.target)) {
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
});