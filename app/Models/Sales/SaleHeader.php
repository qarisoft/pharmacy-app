<?php

namespace App\Models\Sales;

use App\Blamable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleHeader extends Model
{
    /** @use HasFactory<\Database\Factories\Sales\SaleHeaderFactory> */
    use HasFactory,Blamable;
    protected $guarded=[];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'header_id');
    }

    #[Scope]
    public function search(Builder $query, $search): Builder
    {
        return $query->where('note', 'LIKE', "%{$search}%")
            ->orWhere('discount', 'like', "%{$search}%")
            ->orWhere('addition', 'like', "%{$search}%");

    }

    public function updateProfit(): void
    {
        $this->profit_price = $this->items()->sum('profit');
        $this->save();
    }

    public function updateEndPrice(): void
    {
        $a = $this->items()->sum('end_price');

        $this->update(['end_price' => $a+($this->addition??0)-($this->discount??0)]);
    }

    public function itemCount(): int
    {
        return $this->items()->sum('quantity');
    }

    public function additions(): HasMany
    {
        return $this->hasMany(SaleItemPlus::class);
    }
}
