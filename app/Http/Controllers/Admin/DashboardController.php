<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $users = User::where('role', 'user');

        return view('admin.dashboard', [
            'totalUsers' => (clone $users)->count(),
            'activeUsers' => (clone $users)->where('status', 'active')->count(),
            'inactiveUsers' => (clone $users)->where('status', 'inactive')->count(),
            'recentUsers' => (clone $users)->latest()->limit(10)->get(['id', 'name', 'email', 'status', 'created_at']),
        ]);
    }
}
