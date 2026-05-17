<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - Sign Up</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
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

        <div class="right-container">
            <div class="card">

                <h1>Welcome Reader!</h1>
                @if($errors->any())
                    <p class="global-error" style="color: red; margin-bottom: 15px;">{{ $errors->first() }}</p>
                @endif

                <form id="signupForm" action="{{ route('signup') }}" method="POST">
                    @csrf

                    <div class="input-group">
                        <input type="text" name="fullname" required
                            value="{{ old('fullname') }}">
                        <label>Full Name</label>
                        <small class="error" style="color: red;">{{ $errors->first('fullname') }}</small>
                    </div>

                    <div class="input-group">
                        <input type="text" name="username" required
                            value="{{ old('username') }}">
                        <label>Username</label>
                        <small class="error" style="color: red;">{{ $errors->first('username') }}</small>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" required
                            value="{{ old('email') }}">
                        <label>Email</label>
                        <small class="error" style="color: red;">{{ $errors->first('email') }}</small>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" required>
                        <label>Password</label>
                        <small class="error" style="color: red;">{{ $errors->first('password') }}</small>
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
                    Already have an account? <a href="/signin">Sign In</a>
                </p>

            </div>
        </div>

        <div class="left-tagline">
            <h1>Every read leaves a letter in</h1>
        </div>

    </div>
    <script src="{{ asset('js/signup.js') }}"></script>

</body>
</html>
