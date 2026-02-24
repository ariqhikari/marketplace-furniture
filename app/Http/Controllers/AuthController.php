<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

/**
 * AuthController — Manual Authentication
 */
class AuthController extends Controller
{
    /**
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            $request->session()->regenerate();

            $message = 'Selamat datang, ' . $user->name . '!';

            if ($user->role === 'ADMIN') {
                return redirect()->route('admin.dashboard')->with('success', $message);
            } elseif ($user->role === 'SELLER') {
                return redirect()->route('dashboard.index')->with('success', $message);
            }

            return redirect()->route('home')->with('success', $message);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        // Auto-login after register
        Auth::login($user);
        $request->session()->regenerate();

        $message = 'Registrasi berhasil! Selamat datang, ' . $user->name;

        if ($user->role === 'SELLER') {
            return redirect()->route('dashboard.index')->with('success', $message);
        }

        return redirect()->route('home')->with('success', $message);
    }

    /**
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}
