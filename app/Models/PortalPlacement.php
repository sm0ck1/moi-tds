<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortalPlacement extends Model
{

    use HasFactory;

    protected $fillable = ['portal_id', 'external_url', 'in_search', 'ping_counter', 'get_to_ping'];

    protected $casts = [
        'updated_at' => 'date:d.m',
        'in_search' => 'boolean',
    ];

    public function portal()
    {
        return $this->belongsTo(Portal::class);
    }
}
