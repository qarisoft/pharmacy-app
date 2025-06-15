<?php

namespace Database\Seeders;

use App\Models\Products\Company;
use App\Models\Products\MeasureUnit;
use App\Models\Products\MeasureUnitName;
use App\Models\Products\Product;
use App\Models\Sales\SaleHeader;
use App\Models\Sales\SaleItem;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleItemSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $headers = require 'database/seeders/data/sale_headers.php';
        SaleHeader::factory()->createMany($headers);
        $data = require 'database/seeders/data/sale_items.php';
        SaleItem::factory()->createMany($data);


    }

}
