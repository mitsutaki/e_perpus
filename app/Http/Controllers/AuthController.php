<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Cek apakah input adalah email atau username
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $credentials = [
            $field     => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('login');
    }

   public function logout(Request $request)
{
    // Hapus session di database
    $request->session()->flush();
    // Hapus semua cookie
    Cookie::forget(config('session.cookie'));
    // Logout user
    Auth::logout();
    // Invalidate & regenerate token
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('login');
}
}