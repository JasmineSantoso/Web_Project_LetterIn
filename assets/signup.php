<?php
session_start();

$errors = $_SESSION["errors"] ?? [];
$old    = $_SESSION["old"] ?? [];

unset($_SESSION["errors"]);
unset($_SESSION["old"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - Sign Up</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/signup.css">
</head>
<body>

    <!-- Logo -->
    <div class="brand-logo-container">
        <a href="home_unsigned.php">
            <img src="../IMG/logo4.png" alt="LetterIn" class="brand-logo">
        </a>
    </div>

    <!-- Main Layout -->
    <div class="main-container">

        <div class="right-container">
            <div class="card">

                <h1>Welcome Reader!</h1>
                <?php if(isset($errors["email_exists"])): ?>
                    <p class="global-error"><?= htmlspecialchars($errors["email_exists"]) ?></p>
                <?php endif; ?>

                <form id="signupForm" action="/Web_Project_LetterIn/PHP/process_signup.php" method="POST">

                    <div class="input-group">
                        <input type="text" name="fullname" required
                            value="<?= htmlspecialchars($old["fullname"] ?? "") ?>">
                        <label>Full Name</label>
                        <small class="error">
                            <?= isset($errors["fullname"]) ? htmlspecialchars($errors["fullname"]) : "" ?>
                        </small>
                    </div>

                    <div class="input-group">
                        <input type="text" name="username" required
                            value="<?= htmlspecialchars($old["username"] ?? "") ?>">
                        <label>Username</label>
                        <small class="error">
                            <?= isset($errors["username"]) ? htmlspecialchars($errors["username"]) : "" ?>
                        </small>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" required
                            value="<?= htmlspecialchars($old["email"] ?? "") ?>">
                        <label>Email</label>
                        <small class="error">
                            <?= isset($errors["email"]) ? htmlspecialchars($errors["email"]) : "" ?>
                        </small>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" required>
                        <label>Password</label>
                        <small class="error">
                            <?= isset($errors["password"]) ? htmlspecialchars($errors["password"]) : "" ?>
                        </small>
                    </div>

                    <div class="options">
                        <label><input type="checkbox"> Remember me</label>
                    </div>

                    <button class="btn" type="submit">Sign up</button>

                    <button class="btn google" type="button">
                        <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png">
                        Sign up with Google
                    </button>

                </form>

                <p class="signup">
                    Already have an account? <a href="signin.php">Sign In</a>
                </p>

            </div>
        </div>

        <div class="left-tagline">
            <h1>Every read leaves a letter in</h1>
        </div>

    </div>
    <script src="../JS/signup.js"></script>

</body>
</html>