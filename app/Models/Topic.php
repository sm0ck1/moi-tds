<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateSlug($post->name);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('name') && empty($post->slug)) {
                $post->slug = static::generateSlug($post->name);
            }
        });
    }

    public static function generateSlug($title): string
    {
        $slug = Str::slug($title);

        $count = static::where('slug', $slug)->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function portals()
    {
        return $this->hasMany(Portal::class);
    }
}
