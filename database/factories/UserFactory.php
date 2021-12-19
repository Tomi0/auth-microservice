<?php

namespace Database\Factories;

use Authentication\Infrastructure\Domain\Model\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape([
        'username' => "string",
        'email' => "string",
        'email_verified_at' => "\Illuminate\Support\Carbon",
        'password' => "string"
    ])]
    public function definition(): array
    {
        return [
            'username' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
}
