<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/signin.css">
</head>
<body>

    <div class="brand-logo-container">
        <a href="home_unsigned.html">
            <img src="../IMG/logo4.png" alt="LetterIn" class="brand-logo">
        </a>
    </div>

    <div class="main-container">
        
        <div class="left-tagline">
            <h1>Every read leaves a letter in</h1>
        </div>

        <div class="login-card">
            <h2 class="welcome-title">Welcome Back<br>Reader!</h2>
            
            <form action="#">
                <div class="input-group">
                    <input type="email" id="email" placeholder="" required>
                    <label for="email">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" id="password" placeholder="" required>
                    <label for="password">Password</label>
                </div>

                <div class="options-row">
                    <label class="remember-me">
                        <input type="checkbox"> Remember me
                    </label>
                    <a href="#" class="forgot-password">Forget your password?</a>
                </div>

                <button type="submit" class="btn btn-signin">Sign in</button>
                
                <button type="button" class="btn btn-google">
                    <span class="google-icon">G</span> Sign in with Google
                    </button>
            </form>

            <div class="signup-footer">
                Don't have an account? <a href="signup.html">Sign Up</a>
            </div>
        </div>
    </div>

</body>
</html>