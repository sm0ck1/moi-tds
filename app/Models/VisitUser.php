<?php

namespace App\Models;

use App\Models\Traits\HasCountry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitUser extends Model
{
    use HasFactory, HasCountry;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'referrer',
        'visit_date',
        'country_code',
        'device_type',
        'visit_count',

        'partner_link_id',
        'portal_id',
        'uniq_user_hash'
    ];

    protected $appends = [
        'country_name', 'country_flag'
    ];

    public function partnerLink(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PartnerLink::class);
    }

    public function portal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Portal::class);
    }

    public function partner(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            Partner::class,
            Portal::class,
            'id', // Foreign key на portalPlacement
            'id', // Foreign key на portal
            'portal_id', // Local key в visit_users
            'partner_id' // Local key в portal_placements
        );
    }
}
