<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerLink extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'helper_text',
        'partner_id',
        'topic_id',
    ];

    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function topic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
