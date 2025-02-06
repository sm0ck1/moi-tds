<?php

namespace Database\Factories;

use App\Models\Portal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortalPlacement>
 */
class PortalPlacementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'portal_id' => Portal::query()->inRandomOrder()->value('id'),
            'external_url' => $this->faker->url,
        ];
    }
}
