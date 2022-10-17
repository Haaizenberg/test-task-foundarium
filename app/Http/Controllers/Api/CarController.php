<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Cars\CarRequest;
use App\Models\Car;
use App\Models\User;
use App\Services\CarUsingManager;

class CarController extends \App\Http\Controllers\Controller
{
    /**
     * Бронирует машину за польователем
     * 
     * @param CarRequest $request
     * @param App\Models\Car $car автомобиль
     * 
     * @return CarResource
     */
    public function book(CarRequest $request, Car $car)
    {
        $user = User::getById($request->validated('user_id'));
        if (CarUsingManager::bookCarByUser($user, $car)) {
            return response('Success');
        } else {
            return response('Error', 500);
        }
    }


    /**
     * Особождает машину от использования пользователем
     * 
     * @param App\Models\Car $car автомобиль
     * 
     * @return CarResource
     */
    public function release(CarRequest $request, Car $car)
    {
        $user = User::getById($request->validated('user_id'));
        if (CarUsingManager::releaseCar($user, $car)) {
            return response('Success');
        } else {
            return response('Error', 500);
        }
    }
}
