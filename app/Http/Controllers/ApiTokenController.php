<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Создает токен API для существующего или или нового пользователя.
     * Если пользователя с переданными email и password не существует,
     * то создаётся новый пользователь.
     * 
     * @param App\Http\Requests\LoginRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public static function createToken(LoginRequest $request): JsonResponse
    {
        $user = User::getByEmail($request->validated('email')) ?? 
            User::createFrom($request->validated('email'), $request->validated('password'));

        if (is_null($user)) {
            return response()->json(['error' => 'Ошибка получения пользователя.'], options:JSON_UNESCAPED_UNICODE);
        }

        $token = $user->createToken('test');
        return response()->json(['token' => $token->plainTextToken]);
    }
}
