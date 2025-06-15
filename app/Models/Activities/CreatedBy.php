<?php

namespace App\Models\Activities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CreatedBy extends Model
{
    /** @use HasFactory<\Database\Factories\Activities\CreatedByFactory> */
    use HasFactory;


    protected $fillable=[
        'user_id',
    ];


    public function created_by(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
