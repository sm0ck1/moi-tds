<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortalPlacement extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = ['portal_id', 'external_url'];

    public function portal()
    {
        return $this->belongsTo(Portal::class);
    }
}
