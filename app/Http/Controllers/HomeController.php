<?php

namespace App\Http\Controllers;

use App\Models\ManageOrder;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
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
        $userRoles = Auth::user()->getRoleNames();
        $userProjectId = $user->project_id;

        $totalUsers = User::query()
            ->when($userProjectId, fn ($q) => $q->where('project_id', $userProjectId))
            ->count();
        $totalRoles = Role::count();
        $totalHouses = Property::query()
            ->when($userProjectId, fn ($q) => $q->where('project_id', $userProjectId))
            ->where('type', 'house')
            ->count();
        $totalShops = Property::query()
            ->when($userProjectId, fn ($q) => $q->where('project_id', $userProjectId))
            ->where('type', 'shop')
            ->count();
        $totalBookings = Booking::query()
            ->when($userProjectId, fn ($q) => $q->where('project_id', $userProjectId))
            ->count();
        $rolesWithUserCount = Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get(['id', 'name']);

        $roleLabels = $rolesWithUserCount->pluck('name')->values();
        $roleUserCounts = $rolesWithUserCount->pluck('users_count')->values();

        // if($userRoles[0] == 'Admin' || $userRoles[0] == 'Associate' || $userRoles[0] == 'OBS Coustomer Service'){
            return view('dashboard', compact(
                'user',
                'userRoles',
                'totalUsers',
                'totalRoles',
                'roleLabels',
                'roleUserCounts',
                'totalHouses',
                'totalShops',
                'totalBookings'
            ));
        // }
        return redirect()->route('manage-order');
    }
}
