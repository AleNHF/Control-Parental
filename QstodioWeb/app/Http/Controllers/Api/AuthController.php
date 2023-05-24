<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * This endpoint is for logging into the tutor app 
     * Required:
     *      email, password
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email o contraseña incorrecto'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * This endpoint is for get profile of user tutor into the tutor app
     * Parameter: id -> user
     */

    public function profile($userId)
    {
        $user = User::findOrFail($userId);
        $tutor = Tutor::findOrFail($user->tutor_id);

        $data = [
            'user' => $user,
            'tutor' => $tutor
        ];

        return response()->json([
            'message' => 'Datos del Usuario',
            'data' => $data,
        ]);
    }

    /**
     * This endpoint is for logout into the tutor app
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "msg" => "Cierre de Sesión",
            "data" => $request->user()
        ]);
    }
}