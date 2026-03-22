<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Author;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('author')->check()) {
            return redirect('/author/dashboard');
        }
        return view('author.login');
    }

    public function showSignup()
    {
        if (Auth::guard('author')->check()) {
            return redirect('/author/dashboard');
        }
        return view('author.signup');
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:authors',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $author = Author::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active'
        ]);

        Auth::guard('author')->login($author);

        return redirect('/author/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['status'] = 'active';

        if (Auth::guard('author')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/author/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('author')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/author/login');
    }
}
