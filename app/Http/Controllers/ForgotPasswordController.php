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
        // Generar un CAPTCHA matemático simple
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $captchaAnswer = $num1 + $num2;
        
        // Guardar la respuesta en sesión
        session(['captcha_answer' => $captchaAnswer]);
        
        return view('paginaWeb.forgotPassword', [
            'captcha_question' => "$num1 + $num2"
        ]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validación básica
        $request->validate([
            'Correo' => 'required|email|exists:usuarios,Correo',
            'captcha' => 'required|numeric',
        ], [
            'Correo.required' => 'El correo electrónico es obligatorio.',
            'Correo.email' => 'Debe proporcionar un correo electrónico válido.',
            'Correo.exists' => 'No encontramos un usuario con ese correo electrónico.',
            'captcha.required' => 'Debe resolver el CAPTCHA.',
            'captcha.numeric' => 'El CAPTCHA debe ser un número.',
        ]);

        // Verificar CAPTCHA
        if (!session()->has('captcha_answer') || 
            (int)$request->captcha !== (int)session('captcha_answer')) {
            
            // Regenerar CAPTCHA en caso de error
            $num1 = rand(1, 10);
            $num2 = rand(1, 10);
            session(['captcha_answer' => $num1 + $num2]);
            
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['captcha' => 'La respuesta del CAPTCHA es incorrecta.'])
                ->with('captcha_question', "$num1 + $num2");
        }

        // Limpiar el CAPTCHA usado
        session()->forget('captcha_answer');

        // Verificar intentos recientes (protección contra spam)
        $recentAttempt = DB::table('password_reset_tokens')
            ->where('Correo', $request->Correo)
            ->where('created_at', '>', Carbon::now()->subMinutes(2))
            ->first();

        if ($recentAttempt) {
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['Correo' => 'Por favor espera 2 minutos antes de solicitar otro enlace.']);
        }

        // Buscar usuario
        $usuario = Usuario::where('Correo', $request->Correo)->first();

        if (!$usuario) {
            return back()->withErrors(['Correo' => 'No podemos encontrar un usuario con esa dirección de correo electrónico.']);
        }

        // Generar token único
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

        // Enviar email
        try {
            Mail::send('emails.password_reset', ['token' => $token, 'usuario' => $usuario], function($message) use ($request) {
                $message->to($request->Correo);
                $message->subject('Recuperación de Contraseña - Jóvenes Ingenieros');
            });
            
            return back()->with('status', '¡Revisa tu correo electrónico! Te hemos enviado un enlace para restablecer tu contraseña. El enlace expirará en 15 minutos.');
        } catch (\Exception $e) {
            return back()->withErrors(['Correo' => 'Hubo un error al enviar el correo. Por favor, intenta nuevamente.']);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        return view('paginaWeb.resetPassword', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'Correo' => 'required|email|exists:usuarios,Correo',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // Buscar el token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('Correo', $request->Correo)
            ->first();

        // Verificar si existe el registro
        if (!$resetRecord) {
            return back()->withErrors(['Correo' => 'Este enlace de restablecimiento no es válido.']);
        }

        // Verificar si el token ha expirado (15 minutos)
        $tokenCreatedAt = Carbon::parse($resetRecord->created_at);
        if (Carbon::now()->diffInMinutes($tokenCreatedAt) > 15) {
            // Eliminar token expirado
            DB::table('password_reset_tokens')->where('Correo', $request->Correo)->delete();
            
            return back()->withErrors(['token' => 'Este enlace ha expirado. Por favor, solicita uno nuevo.']);
        }

        // Verificar que el token coincida
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['token' => 'Este enlace de restablecimiento no es válido.']);
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