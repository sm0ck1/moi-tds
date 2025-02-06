<?php

namespace Database\Factories;

use App\Helpers\MakeShortCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portal>
 */
class PortalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'short_url' => MakeShortCode::make(8),
            'note' => $this->faker->text,
            'topic_id' => \App\Models\Topic::query()->inRandomOrder()->value('id'),
        ];
    }
}
