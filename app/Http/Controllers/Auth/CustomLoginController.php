<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class CustomLoginController extends Controller
{
    /**
     * Maneja el inicio de sesión validando credenciales
     * y redirigiendo según el rol del usuario.
     */
    public function login(Request $request)
    {
        $request->validate([
            'Correo' => 'required|email',
            'Contrasenia' => 'required',
        ]);

        $email = $request->input('Correo');
        $password = $request->input('Contrasenia');

        // Obtiene el usuario asociado al correo
        $usuario = Usuario::where('Correo', $email)->first();

        if (! $usuario) {
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['Credenciales' => 'Credenciales incorrectas']);
        }

        $stored = (string) $usuario->Contrasenia;
        $ok = false;

        // Verifica la contraseña según si está hasheada o en texto plano
        if (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0 || strpos($stored, '$argon2') === 0) {
            if (Hash::check($password, $stored)) $ok = true;
        } else {
            if (hash_equals($stored, (string)$password)) $ok = true;
        }

        if (! $ok) {
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['Credenciales' => 'Credenciales incorrectas']);
        }

        // Autentica al usuario en el sistema
        Auth::login($usuario);

        // Obtiene el rol de la persona asociada y redirige
        $rol = $usuario->persona ? $usuario->persona->Id_roles : null;

        if ($rol == 1) { 
            return redirect()->route('admin.dashboard');
        } elseif ($rol == 2) { 
            return redirect()->route('home.profesor');
        } elseif ($rol == 3) { 
            return redirect()->route('home.tutor');
        } else {
            return redirect('/');
        }
    }

    /**
     * Cierra la sesión activa y redirige al login.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('status', 'Sesión cerrada');
    }
}
