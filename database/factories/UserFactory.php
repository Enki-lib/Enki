<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->firstName(),
            'sobrenome' => fake()->lastName(),
            'cpf' => fake()->numerify('###########'), // 11 digits
            'data_nascimento' => fake()->date(),
            'senha' => static::$password ??= Hash::make('password'),
            'email' => fake()->unique()->safeEmail(),
            'rua' => fake()->streetName(),
            'numero' => fake()->buildingNumber(),
            'bairro' => fake()->word(),
            'cidade' => fake()->city(),
            'complemento' => fake()->optional()->secondaryAddress(),
            'estado' => fake()->regexify('[A-Z]{2}'), // 2 letter state code
            'api_token' => null
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'api_token' => null
        ]);
    }
}
