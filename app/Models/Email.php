<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Email extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'domain_id',
        'subject',
        'unisender_send_date_at',
        'created_at',
    ];
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;


    /**
     * Получает из БД emails по id-s
     * 
     * @param array $ids массив id записей
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function deleteByIdNotIn(array $ids)
    {
        try {
            // $result = self::where('id', 'in', implode(', ', $ids))->delete();
            $result = self::whereRaw('id not in (' . implode(', ', $ids) . ')')->delete();

        } catch (\Throwable $th) {
            Log::error('Ошибка удаления emails not in ids: ' . $th->getMessage());
            $result = -1;
        }

        return $result;
    }


    /**
     * Обновляет существующюю запись иили же создает новую в случае отсутствия
     * 
     * Возвращает true в случае успеха и false в ином случае
     * 
     * @param array $attributes массив атрибутов Email
     * @return bool
     */
    public static function updateOrCreateFrom(array $attributes): bool
    {
        try {
            Email::updateOrCreate(
                ['id' => $attributes['id']],
                [
                    'domain_id' => $attributes['domain_id'],
                    'subject' => $attributes['subject'],
                    'unisender_send_date_at' => date('Y.m.d H:i', strtotime($attributes['unisender_send_date_at'])) ,
                    'created_at' => $attributes['created_at'],
                ]
            );
        } catch (\Throwable $th) {
            Log::error('Ошибка обновления\создания email-а: ' . $th->getMessage());
            return false;
        }

        return true;
    }
}
