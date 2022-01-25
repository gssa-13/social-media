<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\User;

class DatabaseNotificationFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     */
    protected $model = DatabaseNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid()->toString(),
            'type' => 'App\\Notifications\\ExampleNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => User::factory()->create(),
            'data' => [
                'link' => url('/'),
                'message' => 'Notification message',
            ],
            'read_at' => null
        ];
    }
}
