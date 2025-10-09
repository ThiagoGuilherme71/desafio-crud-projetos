<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registra um novo usuário no sistema
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'O nome é obrigatório.',
                'name.max' => 'O nome não pode ter mais de 255 caracteres.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'O e-mail deve ser válido.',
                'email.unique' => 'Este e-mail já está cadastrado.',
                'password.required' => 'A senha é obrigatória.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.confirmed' => 'A confirmação de senha não corresponde.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = Auth::guard('api')->login($user);

            // Armazenando o token na sessão
            session(['jwt_token' => $token]);
            session()->save();

            return redirect()->route('home')->with('success', 'Cadastro realizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao realizar cadastro: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário de registro
     *
     * @return \Illuminate\View\View
     */
    public function register_form()
    {
        try {
            return view('auth.register');
        } catch (\Exception $e) {
            return redirect()->route('login_form')
                ->with('error', 'Erro ao carregar formulário de registro.');
        }
    }

    /**
     * Exibe o formulário de login
     *
     * @return \Illuminate\View\View
     */
    public function login_form()
    {
        try {
            return view('auth.login');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário de login.');
        }
    }

    /**
     * Autentica o usuário no sistema
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'O e-mail deve ser válido.',
                'password.required' => 'A senha é obrigatória.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $credentials = $request->only('email', 'password');

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return back()->with('error', 'Credenciais inválidas')->withInput();
            }

            // Armazenando o token na sessão
            session(['jwt_token' => $token]);
            session()->save();

            return redirect()->route('home')->with('success', 'Login realizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao realizar login: ' . $e->getMessage());
        }
    }

    /**
     * Retorna informações do usuário autenticado (API)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            return response()->json([
                'success' => true,
                'user' => Auth::guard('api')->user()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar usuário: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Realiza logout via API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            Auth::guard('api')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao realizar logout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Realiza logout via web e redireciona para login
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout_web()
    {
        // Limpa a sessão (não precisa invalidar o token JWT manualmente)
        session()->forget('jwt_token');
        session()->flush();

        // Logout do guard web
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        // Regenera o token CSRF para segurança
        request()->session()->regenerate();

        return redirect()->route('login_form')->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Atualiza o token JWT (refresh)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return response()->json([
                'success' => true,
                'token' => Auth::guard('api')->refresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar token: ' . $e->getMessage()
            ], 500);
        }
    }
}
