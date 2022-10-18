<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarBookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест использования незанятой машины пользователем, не использующим машину.
     *
     * @return void
     */
    public function test_book_free_car_by_free_user()
    {
        Car::factory()
            ->count(10)
            ->create();

        User::factory()
            ->count(10)
            ->create();

        $testCar = Car::inRandomOrder()->first();
        $testUser = User::inRandomOrder()->first();

        $response = $this->post('/api/car/book/' . $testCar['id'], [
            'user_id' => $testUser['id']
        ]);

        // $response->assertStatus(200);
        $this->assertDatabaseHas('cars', [
            'id' => $testCar['id'],
            'user_id' => $testUser['id'],
        ]);
    }


    /**
     * Тест использования занятой машины другим свободным пользователем.
     *
     * @return void
     */
    public function test_book_used_car_by_free_user()
    {
        $carUser = User::factory()->create();
        $freeUser = User::factory()->create();

        $car = Car::factory()
            ->for($carUser)
            ->create();
        

        $response = $this->post('/api/car/book/' . $car['id'], [
            'user_id' => $freeUser['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => $carUser['id'],
        ]);
    }


    /**
     * Тест использования занятой машины её пользователем.
     *
     * @return void
     */
    public function test_book_used_car_by_its_user()
    {
        $carUser = User::factory()->create();

        $car = Car::factory()
            ->for($carUser)
            ->create();
        

        $response = $this->post('/api/car/book/' . $car['id'], [
            'user_id' => $carUser['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => $carUser['id'],
        ]);
    }


    /**
     * Тест использования незанятой машины пользователем, использующим другую машину.
     *
     * @return void
     */
    public function test_book_extra_car_by_one_user()
    {
        $carUser = User::factory()->create();

        $usedCar = Car::factory()
            ->for($carUser)
            ->create();

        $freeCar = Car::factory()->create();
        
        $response = $this->post('/api/car/book/' . $freeCar['id'], [
            'user_id' => $carUser['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $usedCar['id'],
            'user_id' => $carUser['id'],
        ]);
        $this->assertDatabaseHas('cars', [
            'id' => $freeCar['id'],
            'user_id' => null,
        ]);
    }


    /**
     * Тест использования занятой машины пользователем, использующим другую машину.
     *
     * @return void
     */
    public function test_book_used_car_by_user_using_another_car()
    {
        $carUser1 = User::factory()->create();
        $usedCar1 = Car::factory()
            ->for($carUser1)
            ->create();

        $carUser2 = User::factory()->create();
        $usedCar2 = Car::factory()
            ->for($carUser2)
            ->create();
        
        $response = $this->post('/api/car/book/' . $usedCar2['id'], [
            'user_id' => $carUser1['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $usedCar1['id'],
            'user_id' => $carUser1['id'],
        ]);
        $this->assertDatabaseHas('cars', [
            'id' => $usedCar2['id'],
            'user_id' => $carUser2['id'],
        ]);
    }

    
    
    /**
     * Тест использования свободной машины несуществующим в базе пользователем.
     *
     * @return void
     */
    public function test_book_free_car_by_unknown_user()
    {
        $car = Car::factory()->create();
        $randomUserId = random_int(1, 100);
        
        $response = $this->post('/api/car/book/' . $car['id'], [
            'user_id' => $randomUserId,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => null,
        ]);
        $this->assertDatabaseMissing('cars', [
            'user_id' => $randomUserId,
        ]);
    }


    /**
     * Тест использования несуществующей машины свободной пользователем.
     *
     * @return void
     */
    public function test_book_unknown_car_by_free_user()
    {
        $user = User::factory()->create();
        $randomCarId = random_int(1, 100);
        
        $response = $this->post('/api/car/book/' . $randomCarId, [
            'user_id' => $user['id'],
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('cars', [
            'id' => $randomCarId,
            'user_id' => $user['id'],
        ]);
        $this->assertDatabaseMissing('cars', [
            'id' => $randomCarId,
        ]);
    }
}
