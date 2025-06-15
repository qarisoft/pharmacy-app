<?php

namespace App\Models\Sales;

use App\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\Sales\CustomerFactory> */
    use HasFactory, Blamable;
    protected $guarded=[];
}
