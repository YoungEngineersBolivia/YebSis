<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function generate()
    {
        // Generar código aleatorio de 6 caracteres
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captchaCode = '';
        for ($i = 0; $i < 6; $i++) {
            $captchaCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Guardar en sesión
        session(['captcha_code' => $captchaCode]);
        
        // Crear imagen SVG
        $width = 200;
        $height = 60;
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        
        // Fondo con gradiente sutil
        $svg .= '<defs>';
        $svg .= '<linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">';
        $svg .= '<stop offset="0%" style="stop-color:#f8f9fa;stop-opacity:1" />';
        $svg .= '<stop offset="100%" style="stop-color:#e9ecef;stop-opacity:1" />';
        $svg .= '</linearGradient>';
        $svg .= '</defs>';
        
        $svg .= '<rect width="' . $width . '" height="' . $height . '" fill="url(#bg)"/>';
        
        // Borde
        $svg .= '<rect width="' . $width . '" height="' . $height . '" fill="none" stroke="#dee2e6" stroke-width="2"/>';
        
        // Líneas de ruido
        for ($i = 0; $i < 5; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $x2 = rand(0, $width);
            $y2 = rand(0, $height);
            $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="#cccccc" stroke-width="1" opacity="0.5"/>';
        }
        
        // Puntos de ruido
        for ($i = 0; $i < 50; $i++) {
            $cx = rand(0, $width);
            $cy = rand(0, $height);
            $svg .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="1" fill="#adb5bd" opacity="0.4"/>';
        }
        
        // Escribir texto con transformaciones
        $x = 20;
        
        for ($i = 0; $i < strlen($captchaCode); $i++) {
            $angle = rand(-15, 15);
            $y = rand(35, 45);
            $fontSize = rand(26, 30);
            
            // Colores aleatorios oscuros
            $colors = ['#212529', '#495057', '#343a40', '#2c3e50', '#34495e'];
            $color = $colors[array_rand($colors)];
            
            $svg .= '<text x="' . $x . '" y="' . $y . '" ';
            $svg .= 'font-family="Arial, Helvetica, sans-serif" ';
            $svg .= 'font-size="' . $fontSize . '" ';
            $svg .= 'font-weight="bold" ';
            $svg .= 'fill="' . $color . '" ';
            $svg .= 'transform="rotate(' . $angle . ' ' . $x . ' ' . $y . ')">';
            $svg .= htmlspecialchars($captchaCode[$i]);
            $svg .= '</text>';
            
            $x += 28;
        }
        
        $svg .= '</svg>';
        
        // Enviar como imagen SVG
        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    public function refresh()
    {
        // Generar nuevo código
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captchaCode = '';
        for ($i = 0; $i < 6; $i++) {
            $captchaCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        session(['captcha_code' => $captchaCode]);
        
        return response()->json(['success' => true]);
    }
    
    public function verify($input)
    {
        $sessionCode = session('captcha_code');
        return $sessionCode && strtoupper($input) === strtoupper($sessionCode);
    }
}