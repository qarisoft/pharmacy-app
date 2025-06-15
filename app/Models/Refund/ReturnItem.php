<?php

namespace App\Models\Refund;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItem extends Model
{
    /** @use HasFactory<\Database\Factories\Refund\ReturnItemFactory> */
    use HasFactory;

    protected $guarded=[];

    public function header(): BelongsTo
    {
        return $this->belongsTo(ReturnHeader::class,'header_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
