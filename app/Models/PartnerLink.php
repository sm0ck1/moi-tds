<?php

namespace App\Models;

use App\Helpers\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerLink extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'country_code',
        'helper_text',
        'partner_id',
        'topic_id',
    ];

    protected $casts = [
        'country_code' => 'array',
    ];

    protected $appends = [
        'countries',
    ];

    public function getCountriesAttribute(): array
    {
        $country = new Country();
        return array_map(function ($countryCode) use ($country) {
            $current = $country->getByCode($countryCode);
            return [
                'code' => $countryCode,
                'name' => $current['name'],
                'flag' => $current['flag'],
            ];
        }, $this->country_code);
    }

    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function topic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
