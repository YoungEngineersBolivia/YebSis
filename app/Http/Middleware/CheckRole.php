<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $user = auth()->user();
        
        // Cargar la relación persona.rol explícitamente
        $user->load('persona.rol');
        
        // Obtener el rol del usuario (usando el accessor del modelo)
        $userRole = strtolower($user->rol ?? '');
        
        // Debug temporal - COMENTAR después de verificar que funciona
        // dd([
        //     'usuario_id' => $user->Id_usuarios,
        //     'persona_nombre' => $user->persona->Nombre ?? 'N/A',
        //     'rol_detectado' => $userRole,
        //     'roles_permitidos' => $roles,
        // ]);

        // Convertir roles permitidos a minúsculas para comparación
        $rolesPermitidos = array_map('strtolower', $roles);

        // Verificar si el usuario tiene uno de los roles permitidos
        if (in_array($userRole, $rolesPermitidos)) {
            return $next($request);
        }

        // Si no tiene permiso, redirigir según su rol
        return $this->redirectSegunRol($userRole);
    }

    /**
     * Redirigir al usuario según su rol
     */
    private function redirectSegunRol($rol)
    {
        switch ($rol) {
            case 'administrador':
                return redirect()->route('admin.dashboard')
                    ->with('error', 'No tienes permiso para acceder a esa sección');
            
            case 'profesor':
                return redirect()->route('profesor.home')
                    ->with('error', 'No tienes permiso para acceder a esa sección');
            
            case 'tutor':
                return redirect()->route('tutor.home')
                    ->with('error', 'No tienes permiso para acceder a esa sección');
            
            default:
                return redirect()->route('login')
                    ->with('error', 'No tienes un rol válido asignado');
        }
    }
}