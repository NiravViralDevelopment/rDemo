<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $userProjectId = $user->project_id;

        $totalUsers = User::query()
            ->when($userProjectId, fn ($q) => $q->where('project_id', $userProjectId))
            ->count();
        $totalRoles = Role::count();
        $rolesWithUserCount = Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get(['id', 'name']);

        $roleLabels = $rolesWithUserCount->pluck('name')->values();
        $roleUserCounts = $rolesWithUserCount->pluck('users_count')->values();

        return view('dashboard', compact(
            'user',
            'totalUsers',
            'totalRoles',
            'roleLabels',
            'roleUserCounts'
        ));
    }
}
