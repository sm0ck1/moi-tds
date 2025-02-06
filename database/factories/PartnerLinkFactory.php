<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\PartnerLink;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerLinkFactory extends Factory
{
    protected $model = PartnerLink::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'url' => $this->faker->url,
            'helper_text' => $this->faker->text(50),
            'partner_id' => Partner::query()->inRandomOrder()->value('id'),
            'topic_id' => Topic::query()->inRandomOrder()->value('id'),
        ];
    }
}
