<?php

namespace App\Models\Products;

use App\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasureUnitName extends Model
{
    /** @use HasFactory<\Database\Factories\Products\MeasureUnitNameFactory> */
    use HasFactory,Blamable;
    protected $guarded=[];
}
