<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f1f5f9; color: #172554; font-family: Arial, sans-serif; }
        .card { width: min(100%, 470px); padding: 38px; background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 18px 50px #0f172a14; }
        .badge { width: 54px; height: 54px; display: grid; place-items: center; margin: 0 auto 18px; border-radius: 16px; background: #eff6ff; color: #1d4ed8; font-size: 25px; }
        h1 { margin: 0; text-align: center; font-size: 28px; }
        .subtitle { margin: 9px 0 24px; color: #64748b; text-align: center; line-height: 1.5; }
        label { display: block; margin: 16px 0 8px; color: #334155; font-size: 14px; font-weight: 700; }
        input { width: 100%; height: 46px; padding: 0 13px; border: 1px solid #cbd5e1; border-radius: 9px; font: inherit; }
        input:focus { outline: 3px solid #bfdbfe; border-color: #2563eb; }
        .submit { width: 100%; margin-top: 24px; padding: 13px; border: 0; border-radius: 9px; background: #1d4ed8; color: #fff; font-size: 16px; font-weight: 700; cursor: pointer; }
        .submit:hover { background: #1e40af; }
        .submit:focus-visible, a:focus-visible { outline: 3px solid #93c5fd; outline-offset: 2px; }
        .error { margin: 14px 0 0; padding: 11px 13px; border-radius: 8px; background: #fef2f2; color: #b91c1c; font-size: 14px; }
        .links { margin-top: 23px; text-align: center; font-size: 14px; }
        a { color: #1d4ed8; text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media (max-width: 480px) { .card { padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="card">
        <div class="badge" aria-hidden="true">+</div>
        <h1>Create Account</h1>
        <p class="subtitle">Create your account to get started.</p>

        @if ($errors->any())
            <div class="error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required>
            <p class="subtitle">Use at least 8 characters with uppercase and lowercase letters, a number, and a symbol.</p>

            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>

            <button class="submit" type="submit">Create account</button>
        </form>
        <div class="links">Already registered? <a href="{{ route('login') }}">Sign in</a></div>
    </main>
</body>
</html>
