<?php

namespace App\Models\Products;

use App\Models\Store\ProductInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MeasureUnit extends Model
{
    /** @use HasFactory<\Database\Factories\Products\MeasureUnitFactory> */
    use HasFactory;


    protected $guarded = [];

    static function getSellPrice(?int $id, ?int $quantity): float
    {
        return (MeasureUnit::query()->find($id)?->sellPrice() ?? 0) * ($quantity ?? 1);
    }
    static function getCostPrice(?int $id, ?int $quantity): float
    {
        return (MeasureUnit::query()->find($id)?->costPrice() ?? 0) * ($quantity ?? 1);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sellPrice(): float
    {
        return ($this->product?->unit_price * $this->count) - $this->discount;
    }

    public function cost(): HasOne
    {
        return $this->hasOne(ProductInput::class, 'unit_id');
    }

    public function isCost(): bool
    {
        return $this->cost()->exists();
    }


    public function costPrice(?int $unit_cost_price = null)
    {
        $s = $this->product?->lastStoreItem;
        if ($s) {
            $a = $unit_cost_price ?? $s->unit_cost_price;
            if ($s->unit()->exists()) {
                return ($a / $s->unit->count) * $this->count;
            }
            return $a;
        }
        return 0;

    }


    public function profit()
    {
        return $this->sellPrice() - $this->costPrice();
    }

//    protected static function booted(): void
//    {
//        static::created(function (MeasureUnit $unit) {
//            $unit->sell_price = $unit->sellPrice();
//            $unit->cost_price = $unit->costPrice();
//            $unit->save();
//        });
//    }


}
