<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'mark',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Бронирует автомобиль за пользователем
     * 
     * @param User $user
     * 
     * @return bool успешность операции
     */
    public function bookByUser(User $user): bool
    {
        try {
            $this->user()->associate($user);
            $this->save();
        } catch (Exception $e) {
            Log::error('Не удалось забронировать машину за пользователем. ' . $e->getMessage());
            return false;
        }
        
        return true;
    }


    /**
     * Освобождает автомобиль, использующийся пользователем
     * 
     * @return bool успешность операции
     */
    public function release(): bool
    {
        try {
            $this->user()->dissociate();
            $this->save();
        } catch (Exception $e) {
            Log::error('Не удалось освободить машину, используемую пользователем. ' . $e->getMessage());
            return false;
        }
        
        return true;
    }
}
