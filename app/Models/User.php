<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function car()
    {
        return $this->hasOne(Car::class);
    }

    public static function getById(string $userId)
    {
        try {
            $user = self::find($userId);
        } catch (Exception $e) {
            Log::error('Не удалось получить пользователя. ' . $e->getMessage());
            $user = null;
        }

        return $user;
    }
}
