<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarReleaseTest extends TestCase
{
    /**
     * Тест освобождения машины ее пользователем
     *
     * @return void
     */
    public function test_used_car_release_by_its_user()
    {
        $user = User::factory()->create();
        $car = Car::factory()
            ->for($user)
            ->create();
        

        $response = $this->post('/api/car/release/' . $car['id'], [
            'user_id' => $user['id']
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => null,
        ]);
    }


    /**
     * Тест освобождения занятой машины другим пользователем
     *
     * @return void
     */
    public function test_used_car_release_by_another_user()
    {
        $user = User::factory()->create();
        $car = Car::factory()
            ->for($user)
            ->create();
        
        $anotherUser = User::factory()->create();
        
        $response = $this->post('/api/car/release/' . $car['id'], [
            'user_id' => $anotherUser['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => $user['id'],
        ]);
    }


    /**
     * Тест освобождения свободной машины другим свободным пользователем
     *
     * @return void
     */
    public function test_free_car_release_by_another_user()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();
        
        $response = $this->post('/api/car/release/' . $car['id'], [
            'user_id' => $user['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => null,
        ]);
    }


    /**
     * Тест освобождения свободной машины другим занятым пользователем
     *
     * @return void
     */
    public function test_free_car_release_by_user_using_other_car()
    {
        $user = User::factory()->create();
        $car = Car::factory()
            ->for($user)
            ->create();
        
        $freeCar = Car::factory()->create();
        
        $response = $this->post('/api/car/release/' . $freeCar['id'], [
            'user_id' => $user['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $freeCar['id'],
            'user_id' => null,
        ]);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => $user['id'],
        ]);
    }


    /**
     * Тест освобождения занятой машины другим занятым пользователем
     *
     * @return void
     */
    public function test_used_car_release_by_user_using_other_car()
    {
        $user1 = User::factory()->create();
        $car1 = Car::factory()
            ->for($user1)
            ->create();
        
        $user2 = User::factory()->create();
        $car2 = Car::factory()
            ->for($user2)
            ->create();
        
        $response = $this->post('/api/car/release/' . $car1['id'], [
            'user_id' => $user2['id']
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseHas('cars', [
            'id' => $car1['id'],
            'user_id' => $user1['id'],
        ]);
        $this->assertDatabaseHas('cars', [
            'id' => $car2['id'],
            'user_id' => $user2['id'],
        ]);
    }


    /**
     * Тест освобождения несуществующей машины другим занятым пользователем
     *
     * @return void
     */
    public function test_unknown_car_release_by_user_using_other_car()
    {
        $user = User::factory()->create();
        $car = Car::factory()
            ->for($user)
            ->create();
        
        $randomCarId = random_int(1, 100);
        
        $response = $this->post('/api/car/release/' . $randomCarId, [
            'user_id' => $user['id']
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => $user['id'],
        ]);
        $this->assertDatabaseMissing('cars', [
            'id' => $randomCarId,
        ]);
    }


    /**
     * Тест освобождения занятой машины несуществующим пользователем
     *
     * @return void
     */
    public function test_used_car_release_by_unknown_user()
    {
        $user = User::factory()->create();
        $car = Car::factory()
            ->for($user)
            ->create();
        
        $randomUserId = random_int(1, 100);
        
        $response = $this->post('/api/car/release/' . $car['id'], [
            'user_id' => $randomUserId
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('cars', [
            'id' => $car['id'],
            'user_id' => $user['id'],
        ]);
        $this->assertDatabaseMissing('cars', [
            'user_id' => $randomUserId
        ]);
    }
}
