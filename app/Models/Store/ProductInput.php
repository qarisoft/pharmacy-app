<?php

namespace App\Models\Store;

use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use App\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductInput extends Model
{
    /** @use HasFactory<\Database\Factories\Store\ProductInputFactory> */
    use HasFactory;

//    protected $casts=[
//        'payment_type'=>PaymentType::class,
//    ];


    protected $guarded = [];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(ProductInputHeader::class, 'header_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(MeasureUnit::class, 'unit_id');
    }

    protected static function booted(): void
    {
        static::updated(function ($input) {
            $input->header->updateTotalPrice();
        });
        static::created(function ($input) {
            $input->header->updateTotalPrice();
        });
        static::saving(function ($header) {
            $header->total_cost_price = $header->unit_cost_price * $header->quantity;
        });
    }


}
