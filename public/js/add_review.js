// ==========================================
// STAR RATING SYSTEM
// ==========================================
const stars = document.querySelectorAll(".star-rating-input i");
let selectedRating = parseInt(document.getElementById("ratingInput")?.value || 0);

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


// ==========================================
// DEEZER INTEGRATION & SEARCH LOGIC
// ==========================================
const songInput = document.getElementById("songInput");
const searchResults = document.getElementById("searchResults");
const songInputBox = document.getElementById("songInputBox");
const selectedSongContainer = document.getElementById("selectedSongContainer");
const selectedSongArt = document.getElementById("selectedSongArt");
const selectedSongText = document.getElementById("selectedSongText");
const selectedSongAction = document.getElementById("selectedSongAction");
const songsHiddenInputs = document.getElementById("songsHiddenInputs");
const recPills = document.querySelectorAll(".rec-song-pill");

let searchTimeout = null;

// Handle searching with debounce (300ms)
if (songInput) {
    songInput.addEventListener("input", function () {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query === "") {
            searchResults.style.display = "none";
            searchResults.innerHTML = "";
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/deezer/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const tracks = data.data ?? [];
                    renderSearchResults(tracks);
                })
                .catch(err => {
                    console.error("Deezer search failed:", err);
                });
        }, 300);
    });
}

// Render search results
function renderSearchResults(tracks) {
    searchResults.innerHTML = "";

    if (tracks.length === 0) {
        searchResults.innerHTML = `<div style="padding: 12px; color: #888; font-family: var(--font-serif); text-align: center;">No songs found 🎵</div>`;
        searchResults.style.display = "block";
        return;
    }

    tracks.slice(0, 5).forEach(track => {
        const title = track.title ?? "Unknown Title";
        const artist = track.artist?.name ?? "Unknown Artist";
        const albumArt = track.album?.cover_medium ?? (track.album?.cover_small ?? "");
        const previewUrl = track.preview ?? "";

        const item = document.createElement("div");
        item.className = "search-result-item";
        item.innerHTML = `
            <div class="search-result-info">
                <img src="${albumArt}" class="search-result-art">
                <span class="search-result-text">${title} - ${artist}</span>
            </div>
            <div class="search-result-add">
                <i class="fa-solid fa-plus"></i>
            </div>
        `;

        item.addEventListener("click", function (e) {
            e.stopPropagation();
            selectSong(title, artist, albumArt, previewUrl);
        });

        searchResults.appendChild(item);
    });

    searchResults.style.display = "block";
}

// Select a song (from search or recommendations)
function selectSong(title, artist, albumArt, previewUrl = "") {
    // 1. Hide search input box & search results dropdown
    if (songInputBox) songInputBox.style.display = "none";
    if (searchResults) {
        searchResults.style.display = "none";
        searchResults.innerHTML = "";
    }
    if (songInput) songInput.value = "";

    // 2. Populate selected song container details
    if (selectedSongArt) selectedSongArt.src = albumArt || "/images/cover1.jpg";
    if (selectedSongText) selectedSongText.textContent = `${title} - ${artist}`;
    if (selectedSongContainer) {
        selectedSongContainer.setAttribute("data-preview", previewUrl);
        selectedSongContainer.setAttribute("data-title", title);
        selectedSongContainer.setAttribute("data-artist", artist);
        selectedSongContainer.style.display = "flex";

        // Automatically start playing preview if available
        if (previewUrl && typeof window.playSong === "function") {
            setTimeout(() => {
                window.playSong(selectedSongContainer);
            }, 100);
        }
    }

    // 3. Update hidden input values for form submission
    if (songsHiddenInputs) {
        songsHiddenInputs.innerHTML = `
            <input type="hidden" name="songs[0][title]" value="${escapeHtml(title)}">
            <input type="hidden" name="songs[0][artist]" value="${escapeHtml(artist)}">
            <input type="hidden" name="songs[0][album_art]" value="${escapeHtml(albumArt)}">
            <input type="hidden" name="songs[0][preview_url]" value="${escapeHtml(previewUrl)}">
        `;
    }
}

// Play/Pause selected song when clicking selected song info
const selectedSongInfo = document.getElementById("selectedSongInfo");
if (selectedSongInfo) {
    selectedSongInfo.addEventListener("click", function() {
        if (typeof window.playSong === "function") {
            window.playSong(selectedSongContainer);
        }
    });
}

// Remove selected song
if (selectedSongAction) {
    selectedSongAction.addEventListener("click", function () {
        // Stop playing if it's currently playing
        if (selectedSongContainer && selectedSongContainer.classList.contains("playing") && typeof window.playSong === "function") {
            window.playSong(selectedSongContainer);
        }

        // 1. Hide selected song container
        if (selectedSongContainer) selectedSongContainer.style.display = "none";

        // 2. Show back search input box
        if (songInputBox) songInputBox.style.display = "flex";
        if (songInput) {
            songInput.value = "";
            songInput.focus();
        }

        // 3. Clear hidden inputs
        if (songsHiddenInputs) songsHiddenInputs.innerHTML = "";
    });
}

// Handle clicking of recommendation pills
recPills.forEach(pill => {
    pill.addEventListener("click", function () {
        const title = this.getAttribute("data-title");
        const artist = this.getAttribute("data-artist");
        const albumArt = this.getAttribute("data-art");
        const previewUrl = this.getAttribute("data-preview") || "";
        selectSong(title, artist, albumArt, previewUrl);
    });
});

// Close search dropdown when clicking outside
document.addEventListener("click", function (e) {
    if (searchResults && songInput && !searchResults.contains(e.target) && e.target !== songInput) {
        searchResults.style.display = "none";
    }
});

// Helper: Escape HTML
function escapeHtml(str) {
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}


// ==========================================
// BOOKSHELF DROPDOWN SYSTEM
// ==========================================
const bookshelfBtn = document.getElementById("bookshelfBtn");
const bookshelfDropdown = document.getElementById("bookshelfDropdown");
const dropdownItems = document.querySelectorAll(".dropdown-item");

if (bookshelfDropdown) {
    bookshelfDropdown.style.display = "none";
}

if (bookshelfBtn && bookshelfDropdown) {
    bookshelfBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        if (bookshelfDropdown.style.display === "none") {
            bookshelfDropdown.style.display = "block";
        } else {
            bookshelfDropdown.style.display = "none";
        }
    });
}

dropdownItems.forEach(item => {
    item.addEventListener("click", function () {
        dropdownItems.forEach(i => i.classList.remove("active"));
        this.classList.add("active");

        const selectedText = this.querySelector("span").innerText;
        document.getElementById("bookshelfInput").value = selectedText;

        bookshelfBtn.innerHTML = `
            ${selectedText}
            <i class="fa-solid fa-chevron-down"></i>
        `;
        bookshelfDropdown.style.display = "none";
    });
});

document.addEventListener("click", function (e) {
    if (bookshelfBtn && bookshelfDropdown) {
        if (!bookshelfBtn.contains(e.target) && !bookshelfDropdown.contains(e.target)) {
            bookshelfDropdown.style.display = "none";
        }
    }
});


// ==========================================
// FORM VALIDATION BEFORE SUBMIT
// ==========================================
const reviewForm = document.getElementById("reviewForm");

if (reviewForm) {
    reviewForm.addEventListener("submit", function (e) {
        const rating = document.getElementById("ratingInput").value;
        const reviewText = document.querySelector(".review-textarea").value.trim();
        
        if (rating == 0) {
            e.preventDefault();
            alert("Tolong beri rating terlebih dahulu (klik bintang) ⭐");
            return;
        }

        if (reviewText === "") {
            e.preventDefault();
            alert("Tolong tulis review kamu terlebih dahulu ✍️");
            return;
        }
    });
}
