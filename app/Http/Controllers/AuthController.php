<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
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

        return redirect()->route('home');
    }

    public function register_form()
    {
        return view('auth.register');
    }
    public function login_form()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
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

        return redirect()->route('home');
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => Auth::guard('api')->user()
        ]);
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function logout_web()
    {
        // Invalidar o token JWT
        Auth::guard('api')->logout();

        // Limpa a sessão
        session()->forget('jwt_token');
        Auth::guard('web')->logout();

        return redirect()->route('login_form');
    }

    public function refresh()
    {
        return response()->json([
            'success' => true,
            'token' => Auth::guard('api')->refresh()
        ]);
    }
}
