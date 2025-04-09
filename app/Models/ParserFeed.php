<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParserFeed extends Model
{
    protected $fillable = [
        'url',
        'type', // rss, sitemap
        'status',
        'last_update',
    ];
}
