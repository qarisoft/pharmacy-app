<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\Products\CompanyFactory> */
    use HasFactory;

    protected $fillable=['name','address','phone','email'];
}
