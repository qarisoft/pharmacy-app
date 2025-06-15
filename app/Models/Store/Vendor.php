<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\Store\VendorFactory> */
    use HasFactory;
    protected $fillable=['name','address','phone','email'];
}
