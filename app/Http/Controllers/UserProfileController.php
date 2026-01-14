<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function profile(): View
    {
        return view('pages.profile');
    }

    public function changePassword(): View
    {
        return view('pages.change_password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = currentUser();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'მიმდინარე პაროლი არასწორია');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'პაროლი წარმატებით განახლდა!');
    }
}
