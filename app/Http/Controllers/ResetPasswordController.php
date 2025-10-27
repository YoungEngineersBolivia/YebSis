<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Muestra el formulario de restablecimiento de contraseña.
     */
    public function showResetForm($token)
    {
        return view('paginaWeb.resetPassword', ['token' => $token]);
    }

    /**
     * Restablece la contraseña del usuario.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'Correo' => 'required|email|exists:usuarios,Correo',
            'password' => 'required|min:8|confirmed',
        ]);

        // Buscar el token en la tabla
        $passwordReset = DB::table('password_reset_tokens')
            ->where('Correo', $request->Correo)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['Correo' => 'No se encontró una solicitud de restablecimiento para este correo.']);
        }

        // Verificar que el token coincida
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['token' => 'El token de restablecimiento es inválido.']);
        }

        // Verificar que no haya expirado (60 minutos)
        $createdAt = Carbon::parse($passwordReset->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            return back()->withErrors(['token' => 'El token de restablecimiento ha expirado.']);
        }

        // Actualizar la contraseña
        $usuario = Usuario::where('Correo', $request->Correo)->first();
        $usuario->Contrasenia = Hash::make($request->password);
        $usuario->save();

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('Correo', $request->Correo)->delete();

        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida exitosamente!');
    }
}