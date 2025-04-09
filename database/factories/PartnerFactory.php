<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'url' => $this->faker->url,
            'login' => $this->faker->userName,
            'password' => $this->faker->password,
        ];
    }
}
