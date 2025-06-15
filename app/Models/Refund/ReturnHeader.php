<?php

namespace App\Models\Refund;

use App\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnHeader extends Model
{
    /** @use HasFactory<\Database\Factories\Refund\ReturnHeaderFactory> */
    use HasFactory,Blamable;

    protected $guarded=[];


    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class,'header_id');
    }
}
