<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Record extends Model
{
    /** @use HasFactory<\Database\Factories\Finance\RecordFactory> */
    use HasFactory;

    protected $fillable=['value','record_type'];


    public function recordable(): MorphTo
    {
        return $this->morphTo();
    }
}
