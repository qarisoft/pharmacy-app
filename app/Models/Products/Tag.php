<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\Products\TagFactory> */
    use HasFactory;


    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
