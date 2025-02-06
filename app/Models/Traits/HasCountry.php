<?php

namespace App\Models\Traits;

use App\Helpers\Country;

trait HasCountry
{

    public function country(): array
    {
        return [
            'name' => $this->getCountryNameAttribute(),
            'flag' => $this->getCountryFlagAttribute(),
            'code' => $this->country_code
        ];
    }

    public function getCountryNameAttribute(): ?string
    {
        if (empty($this->country_code)) {
            return null;
        }
        return Country::getNameByCode($this->country_code);
    }

    public function getCountryFlagAttribute(): ?string
    {
        if (empty($this->country_code)) {
            return null;
        }
        return Country::getFlagByCode($this->country_code);
    }
}
