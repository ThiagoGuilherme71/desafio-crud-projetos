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
        // Pega o token da sessão
        $token = session('jwt_token');

        if (!$token) {
            \Log::warning('Token não encontrado na sessão');
            return redirect()->route('login_form')->with('error', 'Você precisa estar autenticado.');
        }

        try {
            // Seta o token no JWTAuth
            JWTAuth::setToken($token);

            // Tenta autenticar o usuário com o token
            $user = JWTAuth::authenticate();

            if (!$user) {
                \Log::warning('Usuário não encontrado com o token');
                session()->forget('jwt_token');
                return redirect()->route('login_form')->with('error', 'Token inválido.');
            }

            \Log::info('Usuário autenticado com sucesso via JWT', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

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

        return $next($request);
    }
}
