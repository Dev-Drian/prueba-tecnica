<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombres = [
            'Juan Carlos', 'María Elena', 'Pedro José', 'Ana María', 'Luis Miguel',
            'Carmen Rosa', 'José Antonio', 'Isabel Cristina', 'Miguel Ángel', 'Laura Patricia'
        ];
        
        $apellidos = [
            'González', 'Rodríguez', 'Martínez', 'López', 'García',
            'Fernández', 'Pérez', 'Sánchez', 'Ramírez', 'Torres'
        ];

        $nombre = $this->faker->randomElement($nombres) . ' ' . $this->faker->randomElement($apellidos);
        $username = Str::slug($nombre) . $this->faker->numberBetween(1, 99);

        return [
            'name' => $nombre,
            'username' => $username,
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
