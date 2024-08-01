<?php

namespace Database\Factories;

use App\Models\GameBankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameBankAccount>
 */
class GameBankAccountFactory extends Factory {
    /*
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameBankAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'id' => (string) Str::uuid(),
            'room_id' => (string) Str::uuid(),
            'player_session_token' => Str::random(60),
            'balance' => 0,
            'is_banker' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
