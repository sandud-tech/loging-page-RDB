<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #f1f5f9; color: #0f172a; font-family: Arial, sans-serif; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 18px max(24px, calc((100% - 1180px) / 2)); background: #0f172a; color: #fff; }
        .brand { font-size: 18px; font-weight: 700; }
        .nav { display: flex; align-items: center; gap: 18px; }
        .nav a { color: #cbd5e1; text-decoration: none; font-size: 14px; }
        .logout { padding: 10px 15px; border: 1px solid #475569; border-radius: 8px; background: transparent; color: #fff; cursor: pointer; font: inherit; }
        main { width: min(100% - 40px, 1180px); margin: 38px auto; }
        .intro { margin-bottom: 26px; }
        h1 { margin: 0 0 8px; font-size: clamp(26px, 4vw, 34px); }
        .muted { color: #64748b; }
        .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat, .panel { border: 1px solid #e2e8f0; border-radius: 14px; background: #fff; box-shadow: 0 8px 25px #0f172a0a; }
        .stat { padding: 22px; }
        .stat-label { color: #64748b; font-size: 14px; }
        .stat-value { display: block; margin-top: 10px; color: #172554; font-size: 30px; font-weight: 700; }
        .panel { overflow: hidden; }
        .panel-heading { padding: 20px 22px; border-bottom: 1px solid #e2e8f0; }
        h2 { margin: 0; font-size: 19px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 14px 22px; border-bottom: 1px solid #f1f5f9; white-space: nowrap; font-size: 14px; }
        th { color: #64748b; font-size: 12px; letter-spacing: .04em; text-transform: uppercase; }
        .status { display: inline-block; padding: 5px 9px; border-radius: 99px; background: #dcfce7; color: #166534; font-size: 12px; }
        .status.inactive { background: #fee2e2; color: #991b1b; }
        .empty { padding: 24px; color: #64748b; text-align: center; }
        button:focus-visible, a:focus-visible { outline: 3px solid #93c5fd; outline-offset: 2px; }
        @media (max-width: 700px) { .stats { grid-template-columns: 1fr; } header { align-items: flex-start; padding: 16px 20px; } .nav { gap: 10px; } main { margin-top: 28px; } }
        @media (max-width: 480px) { header { flex-direction: column; } .nav { width: 100%; justify-content: space-between; } main { width: min(100% - 28px, 1180px); } }
    </style>
</head>
<body>
    <header>
        <div class="brand">Administration</div>
        <nav class="nav" aria-label="Main navigation">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span>{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout" type="submit">Log out</button>
            </form>
        </nav>
    </header>
    <main>
        <section class="intro">
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <div class="muted">{{ auth()->user()->email }} · Administrator</div>
        </section>
        <section class="stats" aria-label="User statistics">
            <article class="stat"><span class="stat-label">Total registered users</span><strong class="stat-value">{{ $totalUsers }}</strong></article>
            <article class="stat"><span class="stat-label">Active users</span><strong class="stat-value">{{ $activeUsers }}</strong></article>
            <article class="stat"><span class="stat-label">Deactivated users</span><strong class="stat-value">{{ $inactiveUsers }}</strong></article>
        </section>
        <section class="panel" aria-labelledby="recent-users">
            <div class="panel-heading"><h2 id="recent-users">Recently registered users</h2></div>
            @if ($recentUsers->isEmpty())
                <div class="empty">No users have registered yet.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Registered</th></tr></thead>
                        <tbody>
                            @foreach ($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="status {{ $user->status === 'inactive' ? 'inactive' : '' }}">{{ ucfirst($user->status) }}</span></td>
                                    <td>{{ $user->created_at->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
