// ==========================
// STAR RATING
// ==========================
const stars = document.querySelectorAll(".star-rating-input i");
let selectedRating = 0;

stars.forEach((star, index) => {
    star.addEventListener("mouseover", () => {
        setStars(index);
    });

    star.addEventListener("mouseout", () => {
        setStars(selectedRating - 1);
    });

    star.addEventListener("click", () => {
        selectedRating = index + 1;
        document.getElementById("ratingInput").value = selectedRating; // Update hidden input
        setStars(index);
    });
});

function setStars(index) {
    stars.forEach((s, i) => {
        if (i <= index) {
            s.classList.replace("fa-regular", "fa-solid");
            s.classList.add("active-star"); 
        } else {
            s.classList.replace("fa-solid", "fa-regular");
            s.classList.remove("active-star"); 
        }
    });
}


// ==========================
// ADD SONG LIKE INSTAGRAM
// ==========================
const songInput = document.getElementById("songInput");
const songTags = document.querySelector(".song-tags");

// daftar cover tersedia
const covers = [
    "/images/cover1.jpg",
    "/images/cover2.jpg",
    "/images/cover3.jpg",
    "/images/cover4.jpg"
];

// fungsi tambah lagu baru
function addSong(songName) {
    songName = songName.trim();

    // validasi kosong
    if (songName === "") {
        alert("Masukkan lagu dulu 🎵");
        return;
    }

    // cek duplikat
    let exists = false;
    document.querySelectorAll(".song-tag span").forEach(el => {
        if (el.innerText.toLowerCase() === songName.toLowerCase()) {
            exists = true;
        }
    });

    if (exists) {
        alert("Lagu sudah ada!");
        return;
    }

    // buat tag lagu baru
    const tag = document.createElement("div");
    tag.className = "song-tag";

    // Pilih cover random
    const randomCover = covers[Math.floor(Math.random() * covers.length)];

    tag.innerHTML = `
        <img src="${randomCover}" alt="Cover">
        <span>${songName}</span>
        <i class="fa-solid fa-xmark remove-song"></i>
    `;

    songTags.appendChild(tag);
    updateSongsHiddenInputs(); // Update hidden inputs

    // reset input
    songInput.value = "";
}

function updateSongsHiddenInputs() {
    const container = document.getElementById("songsHiddenInputs");
    container.innerHTML = "";
    document.querySelectorAll(".song-tag span").forEach((el, index) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = `songs[]`;
        input.value = el.innerText;
        container.appendChild(input);
    });
}


// ==========================
// ENTER = TAMBAH LAGU
// ==========================
songInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        addSong(songInput.value);
    }
});


// ==========================
// KLIK LAGU REKOMENDASI
// ==========================
document.addEventListener("click", function (e) {
    const tag = e.target.closest(".song-tag");

    // kalau bukan song-tag → stop
    if (!tag) return;

    // kalau klik tombol remove → hapus lagu
    if (e.target.classList.contains("remove-song")) {
        tag.remove();
        updateSongsHiddenInputs(); // Update hidden inputs
        return;
    }

    // ambil nama lagu
    const songName = tag.querySelector("span").innerText;

    // masuk ke input search
    songInput.value = songName;

    // toggle selected
    tag.classList.toggle("selected");
});

// ==========================
// BOOKSHELF DROPDOWN
// ==========================

const bookshelfBtn = document.getElementById("bookshelfBtn");
const bookshelfDropdown = document.getElementById("bookshelfDropdown");
const dropdownItems = document.querySelectorAll(".dropdown-item");

// awalnya dropdown disembunyikan
bookshelfDropdown.style.display = "none";

// klik tombol → buka / tutup dropdown
bookshelfBtn.addEventListener("click", function (e) {
    e.stopPropagation();

    if (bookshelfDropdown.style.display === "none") {
        bookshelfDropdown.style.display = "block";
    } else {
        bookshelfDropdown.style.display = "none";
    }
});

// klik item dropdown
dropdownItems.forEach(item => {
    item.addEventListener("click", function () {

        // hapus warna hijau dari semua item
        dropdownItems.forEach(i => {
            i.classList.remove("active");
        });

        // item yang dipilih jadi hijau
        this.classList.add("active");

        // ambil text item
        const selectedText = this.querySelector("span").innerText;

        // update hidden input
        document.getElementById("bookshelfInput").value = selectedText;

        // ubah isi tombol utama
        bookshelfBtn.innerHTML = `
            ${selectedText}
            <i class="fa-solid fa-chevron-down"></i>
        `;

        // tutup dropdown setelah dipilih
        bookshelfDropdown.style.display = "none";
    });
});

// klik luar area → dropdown tertutup
document.addEventListener("click", function (e) {
    if (
        !bookshelfBtn.contains(e.target) &&
        !bookshelfDropdown.contains(e.target)
    ) {
        bookshelfDropdown.style.display = "none";
    }
});

// ==========================
// FORM VALIDATION
// ==========================
const reviewForm = document.getElementById("reviewForm");

if (reviewForm) {
    reviewForm.addEventListener("submit", function(e) {
        const rating = document.getElementById("ratingInput").value;
        
        if (rating == 0) {
            e.preventDefault();
            alert("Tolong beri rating terlebih dahulu (klik bintang) ⭐");
        }
    });
}
