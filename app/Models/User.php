<?php

namespace App\Models;

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
}
