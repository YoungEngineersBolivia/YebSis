<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class CustomLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'Correo' => 'required|email',
            'Contrasenia' => 'required',
        ]);

        $email = $request->input('Correo');
        $password = $request->input('Contrasenia');

        // Busca el usuario en la tabla 'usuarios' por el campo 'Correo'
        $usuario = Usuario::where('Correo', $email)->first();

        // Si no existe, muestra error
        if (! $usuario) {
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['Credenciales' => 'Credenciales incorrectas']);
        }

        // Compara la contraseña ingresada con la almacenada en la BD
        $stored = (string) $usuario->Contrasenia;
        $ok = false;

        // Si la contraseña está hasheada (bcrypt/argon2), usa Hash::check
        if (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0 || strpos($stored, '$argon2') === 0) {
            if (Hash::check($password, $stored)) $ok = true;
        } else {
            // Si está en texto plano, compara directamente
            if (hash_equals($stored, (string)$password)) $ok = true;
        }

        // Si no coincide, muestra error
        if (! $ok) {
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['Credenciales' => 'Credenciales incorrectas']);
        }

        // Si coincide, inicia sesión y redirige según el rol
        Auth::login($usuario);

        // Obtener el rol desde la relación persona
        $rol = $usuario->persona ? $usuario->persona->Id_roles : null;

        if ($rol == 1) { // 1 = Administrador
            return redirect()->route('admin.dashboard');
        } elseif ($rol == 2) { // 2 = Tutor
            return redirect()->route('home.profesor');
        } elseif ($rol == 3) { // 3 = Profesor
            return redirect()->route('home.tutor');
        } else {
            return redirect('/'); // Redirección por defecto si el rol no es reconocido
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Sesión cerrada');
    }
}
