<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - Sign In</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/signin.css') }}">
</head>
<body>

    <!-- Logo -->
    <div class="brand-logo-container">
        <a href="/">
            <img src="{{ asset('images/logo4.png') }}" alt="LetterIn" class="brand-logo">
        </a>
    </div>

    <!-- Main Layout -->
    <div class="main-container">

        <!-- LEFT -->
        <div class="left-tagline">
            <h1>Every read leaves a letter in</h1>
        </div>

        <!-- RIGHT -->
        <div class="right-container">
            <div class="card">

                <h1>Welcome Back<br>Reader!</h1>
                @if($errors->any())
                    <p class="global-error" style="color: red; margin-bottom: 15px;">{{ $errors->first() }}</p>
                @endif

                <form action="{{ route('signin') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="email" name="email" required value="{{ old('email') }}">
                        <label>Email</label>
                    </div>
    
                    <div class="input-group">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
    
                    <div class="options">
                        <label><input type="checkbox" name="remember"> Remember me</label>
                        <a href="#">Forgot your password?</a>
                    </div>
    
                    <button class="btn" type="submit">Sign in</button>
    
                    <button class="btn google" type="button">
                        <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png">
                        Sign in with Google
                    </button>
                </form>

                <p class="signup">
                    Don't have an account? <a href="{{ route('signup') }}">Sign Up</a>
                </p>

            </div>
        </div>

    </div>

</body>
</html>
