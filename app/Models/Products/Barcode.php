<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barcode extends Model
{
    /** @use HasFactory<\Database\Factories\Products\BarcodeFactory> */
    use HasFactory;
    protected $fillable=['value'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
