<?php

namespace App\Http\Controllers;

use App\Models\User; // Certifique-se de que o modelo User está corretamente importado.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // A localização correta para uso do Facade Auth.
use Illuminate\Http\JsonResponse; // Para retorno de respostas em JSON.

class LoginController extends Controller
{
    /**
     * Handle the user login request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
