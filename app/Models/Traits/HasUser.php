<?php

namespace App\Models\Traits;

use MoonShine\Laravel\Models\MoonshineUser;

trait HasUser
{

    protected static function bootHasUser()
    {
        static::creating(function ($model) {
            $model->moonshine_user_id = auth()->id();
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class);
    }



}
