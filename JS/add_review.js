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

    tag.innerHTML = `
        <img src="../IMG/cover1.jpg" alt="Cover">
        <span>${songName}</span>
        <i class="fa-solid fa-xmark remove-song"></i>
    `;

    songTags.appendChild(tag);

    // reset input
    songInput.value = "";
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
// BUTTON SEND
// ==========================
const sendBtn = document.querySelector(".btn-send");

if (sendBtn) {
    sendBtn.addEventListener("click", () => {
        const review = document.querySelector(".review-textarea").value.trim();

        let songs = [];
        document.querySelectorAll(".song-tag span").forEach(el => {
            songs.push(el.innerText);
        });

        // validasi rating kosong
        if (selectedRating === 0) {
            alert("Tolong beri rating terlebih dahulu");
            return;
        }

        // validasi review kosong
        if (review === "") {
            alert("Tolong beri review terlebih dahulu");
            return;
        }

        // output sementara
        console.log({
            rating: selectedRating,
            review: review,
            songs: songs
        });

        alert("Review berhasil dikirim!");

        // reset review
        document.querySelector(".review-textarea").value = "";
        selectedRating = 0;
        setStars(-1);

        // hapus selected
        document.querySelectorAll(".song-tag").forEach(tag => {
            tag.classList.remove("selected");
        });

        // kosongkan input
        songInput.value = "";
    });
}
