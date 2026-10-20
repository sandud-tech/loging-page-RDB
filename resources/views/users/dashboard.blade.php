<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #f1f5f9; color: #0f172a; font-family: Arial, sans-serif; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 18px max(24px, calc((100% - 1120px) / 2)); background: #0f172a; color: #fff; }
        .brand { font-size: 18px; font-weight: 700; }
        .logout { padding: 10px 15px; border: 1px solid #475569; border-radius: 8px; background: transparent; color: #fff; cursor: pointer; font: inherit; }
        main { width: min(100% - 40px, 1120px); margin: 42px auto; }
        .intro { margin-bottom: 26px; }
        h1 { margin: 0 0 8px; font-size: clamp(26px, 4vw, 34px); }
        .muted { color: #64748b; }
        .panel { padding: 26px; border: 1px solid #e2e8f0; border-radius: 14px; background: #fff; box-shadow: 0 8px 25px #0f172a0a; }
        h2 { margin: 0 0 20px; font-size: 19px; }
        .details { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        .label { display: block; margin-bottom: 7px; color: #64748b; font-size: 13px; }
        .value { overflow-wrap: anywhere; font-weight: 600; }
        a:focus-visible, button:focus-visible { outline: 3px solid #93c5fd; outline-offset: 2px; }
        @media (max-width: 560px) { header { padding: 16px 20px; } main { margin-top: 28px; } .details { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <div class="brand">Account portal</div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout" type="submit">Log out</button>
        </form>
    </header>
    <main>
        <section class="intro">
            <h1>Welcome, {{ $user->name }}</h1>
            <div class="muted">Here is a summary of your account.</div>
        </section>
        <section class="panel" aria-labelledby="profile-heading">
            <h2 id="profile-heading">Profile summary</h2>
            <div class="details">
                <div><span class="label">Full name</span><span class="value">{{ $user->name }}</span></div>
                <div><span class="label">Email address</span><span class="value">{{ $user->email }}</span></div>
                <div><span class="label">Account role</span><span class="value">{{ ucfirst($user->role) }}</span></div>
                <div><span class="label">Account status</span><span class="value">{{ ucfirst($user->status) }}</span></div>
            </div>
        </section>
    </main>
</body>
</html>
