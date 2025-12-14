<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        
        switch ($user->role) {
            case 'ADMIN':
                return redirect('/admin'); 
            case 'ADMINISTRADOR':
                return redirect('/admin');
            case 'DEFENSA_CIVIL':
                return redirect('/defensa_civil');
            case 'FISCALIZADOR':
                return redirect('/main');
            default:
                Auth::logout();
                return redirect('/')->withErrors(['email' => 'Rol no válido']);
        }
    }

    return redirect('/')->withErrors(['email' => 'Credenciales incorrectas']);
}

public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); 
    }

}
