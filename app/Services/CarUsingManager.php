<?php

namespace App\Services;

use App\Models\Car;
use App\Models\User;

class CarUsingManager
{
    public static function isCarFree(Car $car): bool
    {
        return $car->user === null;
    }


    public static function isUserFree(User $user): bool
    {
        return $user->car === null;
    }


    public static function isUserUseCar(User $user, Car $car): bool
    {
        return $user->car['id'] == $car['id'];
    }


    public static function bookCarByUser(User $user, Car $car): bool
    {
        return self::isCarFree($car) && self::isUserFree($user) && $car->bookByUser($user);
    }


    public static function releaseCar(User $user, Car $car): bool
    {
        return self::isUserUseCar($user, $car) && $car->release();
    }
}