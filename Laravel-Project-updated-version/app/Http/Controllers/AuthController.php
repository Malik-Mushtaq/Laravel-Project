<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show Login & Register Forms
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Process Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->role;
            $username = $user->name;
            $email = $user->email;
            $path = '/properties';
            if($username=="Admin"){
                $path = '/admin/dashboard';
            }
             // ✅ Add this
            // Pass data to frontend via flash session
            return redirect()->intended($path)->with('setLocalStorage', [
                'username' => $username,
                'role' => $role,
            ]);
        }

        return back()->with('error', 'Invalid email or password.');
    }

    // Process Register
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'User',
        ]);

        Auth::login($user);

        $role = $user->role;
        $username = $user->name;
        $email = $user->email;

        return redirect()->route('properties.index')->with('setLocalStorage', [
            'username' => $username,
            'role' => $role,
            'email' => $email,
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Clear localStorage on logout via flash message
        return redirect('/login')->with('clearLocalStorage', true);
    }
}
