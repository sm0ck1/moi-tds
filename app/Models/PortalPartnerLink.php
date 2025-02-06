<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortalPartnerLink extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'portal_id',
        'partner_link_id',
        'conditions',
        'priority',
        'is_fallback'
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_fallback' => 'boolean'
    ];

    public function portal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Portal::class);
    }

    public function partnerLink(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PartnerLink::class);
    }
}
