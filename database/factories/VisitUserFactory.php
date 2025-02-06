<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisitUser>
 */
class VisitUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ip_address' => mt_rand(0, 1) ? $this->faker->ipv6 : $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'referrer' => mt_rand(0, 1) ? $this->faker->url : null,
            'country_code' => $this->faker->countryCode,
            'device_type' => $this->faker->randomElement(['desktop', 'mobile', 'tablet']),
            'visit_date' => $this->faker->dateTimeThisMonth,
            'portal_id' => \App\Models\Portal::query()->inRandomOrder()->value('id'),
            'partner_id' => \App\Models\Partner::query()->inRandomOrder()->value('id'),
            'portal_partner_link_id' => \App\Models\PortalPartnerLink::query()->inRandomOrder()->value('id'),
            'uniq_user_hash' => $this->faker->md5(),
        ];
    }
}
