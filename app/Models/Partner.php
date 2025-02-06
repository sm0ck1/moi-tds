<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'login',
        'password'
    ];

    public $timestamps = false;

    public function partnerLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PartnerLink::class);
    }

}
