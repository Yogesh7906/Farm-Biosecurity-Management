<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back! Biosecurity surveillance active.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our biosecurity records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'farm_name' => ['required', 'string', 'max:255'],
            'farm_type' => ['required', 'in:poultry,pig'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        // 1. Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 2. Create Initial Farm Profile for User
        Farm::create([
            'name' => $validated['farm_name'],
            'farm_type' => $validated['farm_type'],
            'location' => $validated['location'],
            'owner_id' => $user->id,
        ]);

        // 3. Log user in
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registration completed successfully! Initial farm profile configured.');
    }

    /**
     * Log user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logged out safely. Keep farm boundaries secured.');
    }
}
