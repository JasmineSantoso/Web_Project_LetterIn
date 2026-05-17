<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - Authentication</title>

    <link rel="stylesheet" href="{{ asset('css/auth_sliding.css') }}">
</head>
<body>

    <div class="main-container @if($mode == 'signup' || $errors->hasAny(['fullname', 'username', 'email', 'password'])) right-panel-active @endif" id="main-container">

        <!-- Logo -->
        <div class="brand-logo-container">
            <a href="/">
                <img src="{{ asset('images/logo4.png') }}" alt="LetterIn" class="brand-logo">
            </a>
        </div>

        <!-- TAGLINE (The "Overlay" side) -->
        <div class="tagline-container">
            <div class="tagline-content">
                <h1>Every read leaves a letter in</h1>
            </div>
        </div>

        <!-- SIGN IN FORM -->
        <div class="form-container sign-in-container">
            <div class="card">
                <h1>Welcome Back<br>Reader!</h1>
                
                @if($errors->any() && !old('signup_attempt'))
                    <p class="global-error">{{ $errors->first() }}</p>
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
                        <a href="#" style="color: #6b3f2a; text-decoration: none;">Forgot your password?</a>
                    </div>
    
                    <button class="btn" type="submit">Sign in</button>
    
                    <button class="btn google" type="button">
                        <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png">
                        Sign in with Google
                    </button>
                </form>

                <p class="signup">
                    Don't have an account? <a id="to-signup">Sign Up</a>
                </p>
            </div>
        </div>

        <!-- SIGN UP FORM -->
        <div class="form-container sign-up-container">
            <div class="card">
                <h1>Welcome Reader!</h1>

                <form id="signupForm" action="{{ route('signup') }}" method="POST">
                    @csrf
                    <input type="hidden" name="signup_attempt" value="1">

                    <div class="input-group">
                        <input type="text" name="fullname" required value="{{ old('fullname') }}">
                        <label>Full Name</label>
                        @if($errors->has('fullname')) <small class="error">{{ $errors->first('fullname') }}</small> @endif
                    </div>

                    <div class="input-group">
                        <input type="text" name="username" required value="{{ old('username') }}">
                        <label>Username</label>
                        @if($errors->has('username')) <small class="error">{{ $errors->first('username') }}</small> @endif
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" required value="{{ old('email') }}">
                        <label>Email</label>
                        @if($errors->has('email')) <small class="error">{{ $errors->first('email') }}</small> @endif
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" required>
                        <label>Password</label>
                        @if($errors->has('password')) <small class="error">{{ $errors->first('password') }}</small> @endif
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
                    Already have an account? <a id="to-signin">Sign In</a>
                </p>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/auth_sliding.js') }}"></script>

</body>
</html>
