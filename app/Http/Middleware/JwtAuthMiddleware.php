<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd('middle executando');
        // Pega o token da sessão
        $token = session('jwt_token');

        if (!$token) {
            return redirect()->route('login_form')->with('error', 'Você precisa estar autenticado.');
        }

        try {
            // Seta o token no JWTAuth
            JWTAuth::setToken($token);

            // Tenta autenticar o usuário com o token
            $user = JWTAuth::authenticate();

            if (!$user) {
                session()->forget('jwt_token');
                return redirect()->route('login_form')->with('error', 'Token inválido.');
            }

            // Adiciona o usuário na requisição
            $request->merge(['auth_user' => $user]);

        } catch (JWTException $e) {
            \Log::error('Exceção JWT', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->forget('jwt_token');
            return redirect()->route('login_form')->with('error', 'Token inválido ou expirado.');
        }
        // dd('middle passou');
        return $next($request);
    }
}
