<?php

namespace App\Models\Refund;

use App\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithDraw extends Model
{
    /** @use HasFactory<\Database\Factories\Refund\WithDrawFactory> */
    use HasFactory,Blamable;

    protected $guarded=[];
}
