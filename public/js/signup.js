document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("signupForm");

    form.addEventListener("submit", function(e) {

        let valid = true;

        const fullname = form.fullname.value.trim();
        const username = form.username.value.trim();
        const email    = form.email.value.trim();
        const password = form.password.value;

        // reset error
        document.querySelectorAll(".error").forEach(el => el.textContent = "");
        // jangan sentuh .global-error (biar pesan server tetap tampil)

        // FULLNAME
        if (fullname === "") {
            showError("fullname", "Full name wajib diisi");
            valid = false;
        }

        // USERNAME (diperbolehkan bebas panjangnya)
        if (username === "") {
            showError("username", "Username wajib diisi");
            valid = false;
        }

        // EMAIL
        const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailValid.test(email)) {
            showError("email", "Format email tidak valid");
            valid = false;
        }

        // PASSWORD
        const passwordValid =
            password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[!@#$%^&*(),.?":{}|<>]/.test(password);

        if (!passwordValid) {
            showError("password", "Password min 8 karakter + huruf besar + angka + simbol");
            valid = false;
        }

        // STOP submit kalau tidak valid
        if (!valid) {
            e.preventDefault();
        }

    });

    function showError(name, message) {
        const input = document.querySelector(`input[name="${name}"]`);
        const container = input.parentElement.querySelector(".error");

        if (container) {
            container.textContent = message;
        }
    }

});