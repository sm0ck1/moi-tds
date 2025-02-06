<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'short_url',
        'bot_url',
        'note',
        'topic_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:d.m.Y',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function portalPartnerLinks()
    {
        return $this->hasMany(PortalPartnerLink::class);
    }



}
