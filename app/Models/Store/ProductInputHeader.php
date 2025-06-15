<?php

namespace App\Models\Store;

use App\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductInputHeader extends Model
{
    /** @use HasFactory<\Database\Factories\Store\ProductInputHeaderFactory> */
    use HasFactory,Blamable;
    protected $guarded=[];

    public function items(): HasMany
    {
        return $this->hasMany(ProductInput::class, 'header_id');
    }

    public function totalCost(): float
    {
        return $this->hasMany(ProductInput::class, 'header_id')->sum('total_cost_price');
    }

    public function updateTotalPrice(): void
    {
        $this->total_price=$this->totalCost();
        $this->save();
    }
//    protected static function booted(): void
//    {
//    }


}
