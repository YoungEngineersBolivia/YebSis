<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Usuario;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('paginaWeb.forgotPassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'Correo' => 'required|email|exists:usuarios,Correo',
        ]);

        // Buscar usuario
        $usuario = Usuario::where('Correo', $request->Correo)->first();

        if (!$usuario) {
            return back()->withErrors(['Correo' => 'No podemos encontrar un usuario con esa dirección de correo electrónico.']);
        }

        // Generar token
        $token = Str::random(64);

        // Guardar en la tabla password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['Correo' => $request->Correo],
            [
                'Correo' => $request->Correo,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Enviar email (necesitas configurar esto)
        // Mail::send(...) o usa $usuario->sendPasswordResetNotification($token);
        
        // Por ahora, simulamos el envío
        try {
            $usuario->sendPasswordResetNotification($token);
            return back()->with('status', '¡Revisa tu correo electrónico para restablecer la contraseña!');
        } catch (\Exception $e) {
            return back()->withErrors(['Correo' => 'Hubo un error al enviar el correo: ' . $e->getMessage()]);
        }
    }
}