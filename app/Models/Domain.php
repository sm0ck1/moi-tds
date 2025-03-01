<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'date_registration',
        'date_end',
        'note',
        'dns_provider',
        'dns_provider_login',
        'is_active_for_ping',
        'is_active_for_code',
    ];

    protected $casts = [
        'date_registration' => 'datetime',
        'date_end' => 'datetime',
    ];
}
