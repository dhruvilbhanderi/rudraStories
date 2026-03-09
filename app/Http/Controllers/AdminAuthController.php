<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session()->get('admin_logged_in') === true) {
            return redirect('/dashboard');
        }

        return view('admin.navFooter.Alogin');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $adminUsername = (string) config('services.admin.username');
        $adminPassword = (string) config('services.admin.password');

        $isValidUsername = hash_equals($adminUsername, (string) $validated['username']);
        $isValidPassword = hash_equals($adminPassword, (string) $validated['password']);

        if (! $isValidUsername || ! $isValidPassword) {
            return redirect('/admin')->with('error', 'Invalid admin credentials.');
        }

        session([
            'admin_logged_in' => true,
            'admin_username' => $validated['username'],
        ]);

        return redirect('/dashboard');
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_username']);

        return redirect('/admin');
    }
}
