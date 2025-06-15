<?php

namespace Database\Seeders;

use App\Models\Products\Company;
use App\Models\Products\MeasureUnit;
use App\Models\Products\MeasureUnitName;
use App\Models\Products\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasureUnitSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $data = require 'database/seeders/data/measure_units.php';
        MeasureUnit::factory()->createMany($data);


    }

}
