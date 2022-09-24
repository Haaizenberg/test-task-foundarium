<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Находит и возвращает пользователя из БД по заданному email
     * 
     * @param string $email
     * @return User|null
     */
    public static function getByEmail(string $email): ?User
    {
        try {
            $user = self::where('email', $email)->firstOr(function () {
                return null;
            });
        } catch (\Throwable $th) {
            Log::error('Ошибка получения пользователя по email: ' . $th->getMessage());
            $user = null;
        }

        return $user;
    }


    /**
     * Создаёт нового пользователя
     * 
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public static function createFrom(string $email, string $password): ?User
    {
        try {
            $user = self::create([
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        } catch (\Throwable $th) {
            Log::error('Ошибка создания пользователя: ' . $th->getMessage());
            $user = null;
        }

        return $user;
    }


    /**
     * Меняет пароль у пользователя по email
     * 
     * Возвращает true в случае успешной смены пароля и false - в ином случае.
     * 
     * @param string $email email пользователя, которому меняем пароль
     * @param string $password новый пароль
     * @return bool 
     */
    public static function changePassword(string $email, string $password): bool
    {
        $user = self::getByEmail($email);
        
        if (is_null($user)) {
            return false;
        }

        try {
            $user->password = Hash::make($password);
            $user->save();
        } catch (\Throwable $th) {
            Log::error('Ошибка смены пароля пользователя: ' . $th->getMessage());
            return false;
        }

        return true;
    }
}
