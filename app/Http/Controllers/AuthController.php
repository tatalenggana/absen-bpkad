<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (auth()->check()) {
            return auth()->user()->isAdmin() 
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        // Jika sudah login, redirect ke dashboard
        if (auth()->check()) {
            return auth()->user()->isAdmin() 
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    /**
     * Handle register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'school_name' => 'required|string|max:255',
            'division' => 'required|in:akuntansi,sekretaria,anggaran,keuangan,perbendaharaan',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        // Create user profile - pastikan berhasil dibuat
        try {
            UserProfile::create([
                'user_id' => $user->id,
                'school_name' => $validated['school_name'],
                'division' => $validated['division'],
            ]);
        } catch (\Exception $e) {
            \Log::error('UserProfile creation error: ' . $e->getMessage());
            return back()->with('error', 'Error membuat profil. Silakan coba lagi.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
