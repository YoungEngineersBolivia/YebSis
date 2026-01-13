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
        // Generar código CAPTCHA y guardarlo en sesión
        $this->generateCaptcha();
        
        return view('paginaWeb.forgotPassword');
    }

    private function generateCaptcha()
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captchaCode = '';
        for ($i = 0; $i < 6; $i++) {
            $captchaCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        session(['captcha_code' => $captchaCode]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validación básica
        $request->validate([
            'Correo' => 'required|email|exists:usuarios,Correo',
            'captcha' => 'required|string',
        ], [
            'Correo.required' => 'El correo electrónico es obligatorio.',
            'Correo.email' => 'Debe proporcionar un correo electrónico válido.',
            'Correo.exists' => 'No encontramos un usuario con ese correo electrónico.',
            'captcha.required' => 'Debe ingresar el código CAPTCHA.',
        ]);

        // Verificar CAPTCHA
        $sessionCaptcha = session('captcha_code');
        if (!$sessionCaptcha || strtoupper($request->captcha) !== strtoupper($sessionCaptcha)) {
            // Regenerar CAPTCHA en caso de error
            $this->generateCaptcha();
            
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['captcha' => 'El código CAPTCHA es incorrecto.']);
        }

        // Limpiar el CAPTCHA usado
        session()->forget('captcha_code');

        // Verificar intentos recientes (protección contra spam)
        $recentAttempt = DB::table('password_reset_tokens')
            ->where('Correo', $request->Correo)
            ->where('created_at', '>', Carbon::now()->subMinutes(2))
            ->first();

        if ($recentAttempt) {
            $this->generateCaptcha();
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['Correo' => 'Por favor espera 2 minutos antes de solicitar otro enlace.']);
        }

        // Buscar usuario
        $usuario = Usuario::where('Correo', $request->Correo)->first();

        if (!$usuario) {
            $this->generateCaptcha();
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

        // Crear URL de restablecimiento - CORREGIDO: usar Correo en vez de email
        $resetUrl = route('password.reset', ['token' => $token, 'Correo' => $request->Correo]);

        // Enviar email
        try {
            Mail::send('emails.password_reset', [
                'token' => $token, 
                'usuario' => $usuario,
                'resetUrl' => $resetUrl
            ], function($message) use ($request) {
                $message->to($request->Correo);
                $message->subject('Recuperación de Contraseña - Jóvenes Ingenieros');
            });
            
            // Verificar si realmente se envió
            if (count(Mail::failures()) > 0) {
                \Log::error('Error al enviar correo a: ' . $request->Correo);
                throw new \Exception('No se pudo enviar el correo');
            }
            
            // Regenerar CAPTCHA para futuros intentos
            $this->generateCaptcha();
            
            return back()->with('status', '¡Revisa tu correo electrónico! Te hemos enviado un enlace para restablecer tu contraseña. El enlace expirará en 15 minutos.');
            
        } catch (\Exception $e) {
            \Log::error('Error en envío de correo: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $this->generateCaptcha();
            return back()
                ->withInput($request->only('Correo'))
                ->withErrors(['Correo' => 'Hubo un error al enviar el correo. Por favor, inténtalo de nuevo más tarde.']);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        // Generar CAPTCHA para el formulario de reset
        $this->generateCaptcha();
        
        // CORREGIDO: Usar Correo en vez de email
        return view('paginaWeb.resetPassword', [
            'token' => $token, 
            'Correo' => $request->Correo
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'Correo' => 'required|email|exists:usuarios,Correo',
            'password' => 'required|min:8|confirmed',
            'captcha' => 'required|string',
        ], [
            'Correo.required' => 'El correo electrónico es obligatorio.',
            'Correo.email' => 'Debe proporcionar un correo electrónico válido.',
            'Correo.exists' => 'No encontramos un usuario con ese correo electrónico.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'captcha.required' => 'Debe ingresar el código CAPTCHA.',
        ]);

        // Verificar CAPTCHA
        $sessionCaptcha = session('captcha_code');
        if (!$sessionCaptcha || strtoupper($request->captcha) !== strtoupper($sessionCaptcha)) {
            $this->generateCaptcha();
            
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'captcha'))
                ->withErrors(['captcha' => 'El código CAPTCHA es incorrecto.']);
        }

        // Limpiar el CAPTCHA usado
        session()->forget('captcha_code');

        // Buscar el token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('Correo', $request->Correo)
            ->first();

        // Verificar si existe el registro
        if (!$resetRecord) {
            $this->generateCaptcha();
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'captcha'))
                ->withErrors(['Correo' => 'Este enlace de restablecimiento no es válido.']);
        }

        // Verificar si el token ha expirado (15 minutos)
        $tokenCreatedAt = Carbon::parse($resetRecord->created_at);
        if (Carbon::now()->diffInMinutes($tokenCreatedAt) > 15) {
            DB::table('password_reset_tokens')->where('Correo', $request->Correo)->delete();
            
            $this->generateCaptcha();
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'captcha'))
                ->withErrors(['token' => 'Este enlace ha expirado. Por favor, solicita uno nuevo.']);
        }

        // Verificar que el token coincida
        if (!Hash::check($request->token, $resetRecord->token)) {
            $this->generateCaptcha();
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'captcha'))
                ->withErrors(['token' => 'Este enlace de restablecimiento no es válido.']);
        }

        // Actualizar la contraseña
        $usuario = Usuario::where('Correo', $request->Correo)->first();
        
        if (!$usuario) {
            $this->generateCaptcha();
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'captcha'))
                ->withErrors(['Correo' => 'No se pudo encontrar el usuario.']);
        }

        $usuario->Contrasenia = Hash::make($request->password);
        $usuario->save();

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('Correo', $request->Correo)->delete();

        // Limpiar sesión
        session()->forget('captcha_code');

        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida exitosamente! Ahora puedes iniciar sesión con tu nueva contraseña.');
    }

    public function refreshCaptcha()
    {
        $this->generateCaptcha();
        return response()->json(['success' => true]);
    }
}