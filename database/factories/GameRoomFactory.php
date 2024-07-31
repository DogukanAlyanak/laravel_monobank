<?php

namespace Database\Factories;

use App\Models\GameRoom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GameRoomFactory extends Factory
{
    /*
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'room_name' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
