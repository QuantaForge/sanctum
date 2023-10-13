<?php

namespace Workbench\Database\Factories;

use QuantaQuirk\Database\Eloquent\Factories\Factory;
use QuantaQuirk\Support\Carbon;
use QuantaQuirk\Sanctum\PersonalAccessToken;

/**
 * @phpstan-type TModel \QuantaQuirk\Sanctum\PersonalAccessToken
 *
 * @extends \QuantaQuirk\Database\Eloquent\Factories\Factory<TModel>
 */
class PersonalAccessTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\QuantaQuirk\Database\Eloquent\Model|TModel>
     */
    protected $model = PersonalAccessToken::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'token' => hash('sha256', 'test'),
            'created_at' => Carbon::now(),
            'expires_at' => null,
        ];
    }
}
