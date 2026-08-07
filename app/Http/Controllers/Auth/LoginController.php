<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->redirectTo(Auth::user()->role));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Your account is not active.',
            ]);
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->intended($this->redirectTo($user->role));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirectTo(string $role): string
    {
        return match ($role) {
            'admin' => '/admin',
            'account' => '/account',
            'client' => '/client',
            default => '/',
        };
    }
}
