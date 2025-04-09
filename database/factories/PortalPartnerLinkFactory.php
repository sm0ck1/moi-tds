<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortalPartnerLink>
 */
class PortalPartnerLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'portal_id' => \App\Models\Portal::query()->inRandomOrder()->value('id'),
            'partner_link_id' => \App\Models\PartnerLink::query()->inRandomOrder()->value('id'),
            'conditions' => [
                'country' => [
                    'operator' => fake()->randomElement(['in', 'not']),
                    'values' => fake()->randomElements(['US', 'UK', 'CA', 'AU', 'DE'], random_int(1, 3)),
                ],
                'device' => [
                    'value' => fake()->randomElement(['desktop', 'mobile']),
                ],
            ],
            'priority' => 0, // будет установлено позже
            'is_fallback' => false, // будет установлено позже
        ];
    }

    /**
     * Configure the factory to create portal partner links with proper order and fallback.
     *
     * @param  int  $count  Number of links to create (including fallback)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forPortal(int $portalId, int $count = 3)
    {
        return $this->sequence(
            ...collect(range(0, $count - 1))->map(function ($index) use ($portalId, $count) {
                $isFallback = $index === $count - 1;

                return [
                    'portal_id' => $portalId,
                    'priority' => $index,
                    'is_fallback' => $isFallback,
                    'conditions' => $isFallback ? [] : [
                        'country' => [
                            'operator' => fake()->randomElement(['in', 'not']),
                            'values' => fake()->randomElements(['US', 'UK', 'CA', 'AU', 'DE'], random_int(1, 3)),
                        ],
                        'device' => [
                            'value' => fake()->randomElement(['desktop', 'mobile']),
                        ],
                    ],
                ];
            })->toArray()
        );
    }
}
