<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        Log::info("Usuário autenticado com sucesso");

        return $this->respondWithToken($token);
    }


    public function logout()
    {
        try {
            auth()->logout();
            Log::info("Logout feito com sucesso");
        } catch (\Throwable $th) {
            Log::error("Falha ao fazer logout");
            return response()->json([
                'error' => 'Erro ao deslogar'
            ], 401);
        }

        return response()->json(['message' => 'Logout feito com sucesso']);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|unique:users|email|string',
                'password' => 'required|string|min:8'
            ],
            [
                'name.required' => 'O nome é obrigatório',
                'email.required' => 'O email é obrigatório',
                'email.unique' => 'Este email já está em uso',
                'email.email' => 'O email deve ser um endereço de email válido',
                'password.required' => 'A senha é obrigatória',
                'password.string' => 'A senha deve ser uma string',
                'password.min' => 'A senha deve ter no minímo 8 caracteres'
            ]
        );

        Log::info("Body da requisição validado");

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        Log::info("Usuário registrado com sucesso");

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'user' => $user
        ], 201);
    }
}
