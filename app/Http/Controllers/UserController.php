<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Возвращает текущего пользователя по валидному токену
     * 
     * @param Illuminate\Http\Request $request
     * @return App\Models\User
     */
    public static function getUser(Request $request): User
    {
       return $request->user();   
    }
}
