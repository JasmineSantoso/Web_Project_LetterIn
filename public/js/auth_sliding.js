document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById("main-container");
    const toSignup = document.getElementById("to-signup");
    const toSignin = document.getElementById("to-signin");

    if (container && toSignup && toSignin) {
        toSignup.addEventListener("click", (e) => {
            e.preventDefault();
            container.classList.add("right-panel-active");
            window.history.pushState(null, "", "/signup");
        });

        toSignin.addEventListener("click", (e) => {
            e.preventDefault();
            container.classList.remove("right-panel-active");
            window.history.pushState(null, "", "/signin");
        });
    }

    // Handle back/forward buttons
    window.addEventListener("popstate", () => {
        if (window.location.pathname.includes('signup')) {
            container.classList.add("right-panel-active");
        } else {
            container.classList.remove("right-panel-active");
        }
    });
});
