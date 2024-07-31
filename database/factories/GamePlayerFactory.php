<?php

namespace Database\Factories;

use App\Models\GamePlayer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GamePlayerFactory extends Factory {
    /*
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GamePlayer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'id' => (string) Str::uuid(),
            'session_token' => Str::random(60),
            'player_name' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
