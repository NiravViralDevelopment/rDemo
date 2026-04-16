<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(){
        $user = Auth::user();
        return view('profile.show', compact('user'));

    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['error' => 'Current password does not match our records.']);
        }
        $user = Auth::user();
        $user->show_password = $request->new_password; 
        $user->password = Hash::make($request->new_password);
        $user->save();
        return back()->with('message', 'Password changed successfully.');
    }
}
