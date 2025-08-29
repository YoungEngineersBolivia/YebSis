<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        return view('paginaWeb.login'); // tu vista
    }

    // Login
    public function login(Request $request)
    {
        // Validación básica
        $request->validate([
            'Correo' => 'required|email',
            'Contrasania' => 'required',
        ]);

        // Debug: revisar datos recibidos
        \Log::info('Intento de login', $request->only('Correo', 'Contrasania'));

        // Intentar autenticación usando Auth
        $credentials = [
            'Correo' => $request->Correo,
            'password' => $request->Contrasania, // Laravel siempre usa 'password'
        ];

        // Debug: revisar credenciales
        \Log::info('Credenciales mapeadas', $credentials);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Debug: usuario autenticado
            \Log::info('Usuario autenticado', ['id' => $user->Id_usuarios, 'correo' => $user->Correo]);

            // Revisar rol del usuario
            $rol = optional($user->persona->rol)->Nombre_rol ?? 'Sin rol';
            \Log::info('Rol del usuario', ['rol' => $rol]);

            // Redirigir según rol
            switch ($rol) {
                case 'Administrador':
                    return redirect()->route('administrador.dashboard');
                case 'Empleado':
                    return redirect()->route('empleado.dashboard');
                case 'Cliente':
                    return redirect()->route('cliente.dashboard');
                default:
                    return redirect('/')->with('warning', 'Usuario sin rol asignado.');
            }
        }

        // Login fallido
        \Log::warning('Login fallido', ['Correo' => $request->Correo]);
        return back()->withErrors([
            'Correo' => 'Correo o contraseña incorrectos.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
