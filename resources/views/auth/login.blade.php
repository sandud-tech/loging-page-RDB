<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f1f5f9; color: #172554; font-family: Arial, sans-serif; }
        .card { width: min(100%, 430px); padding: 38px; background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 18px 50px #0f172a14; }
        .badge { width: 54px; height: 54px; display: grid; place-items: center; margin: 0 auto 18px; border-radius: 16px; background: #eff6ff; color: #1d4ed8; font-size: 25px; }
        h1 { margin: 0; text-align: center; font-size: 28px; }
        .subtitle { margin: 9px 0 28px; color: #64748b; text-align: center; line-height: 1.5; }
        label { display: block; margin: 17px 0 8px; color: #334155; font-size: 14px; font-weight: 700; }
        input[type="email"], input[type="password"], input[type="text"] { width: 100%; height: 46px; padding: 0 13px; border: 1px solid #cbd5e1; border-radius: 9px; font: inherit; }
        input:focus { outline: 3px solid #bfdbfe; border-color: #2563eb; }
        .password-wrap { display: flex; gap: 8px; }
        .password-wrap input { flex: 1; min-width: 0; }
        .toggle { padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 9px; background: #fff; color: #334155; cursor: pointer; }
        .remember { display: flex; align-items: center; gap: 8px; margin-top: 17px; color: #475569; font-size: 14px; font-weight: 400; }
        .submit { width: 100%; margin-top: 24px; padding: 13px; border: 0; border-radius: 9px; background: #1d4ed8; color: #fff; font-size: 16px; font-weight: 700; cursor: pointer; }
        .submit:hover { background: #1e40af; }
        .submit:focus-visible, .toggle:focus-visible, a:focus-visible { outline: 3px solid #93c5fd; outline-offset: 2px; }
        .error, .success { margin: 14px 0 0; padding: 11px 13px; border-radius: 8px; font-size: 14px; }
        .error { background: #fef2f2; color: #b91c1c; }
        .success { background: #f0fdf4; color: #166534; }
        .links { display: grid; gap: 12px; margin-top: 24px; text-align: center; font-size: 14px; }
        a { color: #1d4ed8; text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media (max-width: 480px) { .card { padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="card">
        <div class="badge" aria-hidden="true">●</div>
        <h1>Welcome Back</h1>
        <p class="subtitle">Sign in to continue to your account.</p>

        @if (session('status'))
            <div class="success" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" data-login-form>
            @csrf
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>

            <label for="password">Password</label>
            <div class="password-wrap">
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                <button class="toggle" type="button" data-password-toggle="password" aria-label="Show password">Show</button>
            </div>

            <label class="remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Remember me</label>
            <button class="submit" type="submit">Sign in</button>
        </form>
        <div class="links">
            <a href="{{ route('register') }}">Create an account</a>
            <a href="{{ route('admin.login') }}">Administrator login</a>
        </div>
    </main>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const visible = input.type === 'password';
                input.type = visible ? 'text' : 'password';
                button.textContent = visible ? 'Hide' : 'Show';
                button.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
            });
        });
        document.querySelectorAll('[data-login-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[type="submit"]');
                button.disabled = true;
                button.textContent = 'Signing in…';
            });
        });
    </script>
</body>
</html>
