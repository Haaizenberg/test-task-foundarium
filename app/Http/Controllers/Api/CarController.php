<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Cars\CarRequest;
use App\Models\Car;

class CarController extends \App\Http\Controllers\Controller
{
    /**
     * Бронирует машину за польователем
     * 
     * @param App\Models\Car $car автомобиль
     * 
     * @return CarResource
     */
    public function book(CarRequest $request, Car $car)
    {
        return $car->bookByUser($request->validated('user_id'));
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
        return $car->releaseByUser($request->validated('user_id'));
    }
}
