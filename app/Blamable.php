<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static created(\Closure $param)
 * @method belongsTo(string $class, string $string)
 */
trait Blamable
{
    protected static function bootBlamable(): void
    {
        static::created(function ( $model) {
            $model->update([
                'created_by'=>auth()->id() ?? 1
            ]);
        });
    }


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
