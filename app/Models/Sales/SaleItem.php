<?php

namespace App\Models\Sales;

use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    /** @use HasFactory<\Database\Factories\Sales\SaleItemFactory> */
    use HasFactory;
    protected $with=['product'];
    protected $guarded = [];


    public function header(): BelongsTo
    {
        return $this->belongsTo(SaleHeader::class, 'header_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(MeasureUnit::class, 'unit_id');
    }


    public function getUnitId()
    {
        if ($this->unit()->exists()) {
            return $this->unit->id;
        }
        return $this->product->units()->where('count', $this->type)->first()?->id;
    }

    public function calculateCost()
    {
        $a = $this->product_price;
        return ($a / $this->unit->count) * $this->count;
    }

    public function calculateProfit(): int
    {
        return $this->unit->profit() * $this->quantity;
    }


    public function costPrice()
    {
        return $this->unit->costPrice() * $this->quantity;
    }

    protected static function booted(): void
    {

//        static::creating(function (SaleItem $sale) {
////             $sale->unit_id =
//        });
//        static::created(function (SaleItem $saleItem) {
//            $saleItem->profit = $saleItem->calculateProfit();
//            $saleItem->product_price = $saleItem->product->unit_price;
//            $saleItem->save();
//            $saleItem->header?->updateProfit();
//        });

//        static::updated(function (SaleItem $saleItem) {
//            $saleItem->header?->updateProfit();
//        });

//        static::updating(function (SaleItem $saleItem) {
//            $saleItem->profit = $saleItem->calculateProfit();
//            if ($saleItem->wasChanged(['product_id'])) {
//                $saleItem->product_price = $saleItem->product->unit_price;
//            }
//        });
    }

}
