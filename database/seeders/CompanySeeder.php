<?php

namespace Database\Seeders;

use App\Models\Products\Company;
use App\Models\Products\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Company::factory()->createMany([
            ['name'=>'Al-Jabal'],
            ['name'=>'Al-Dawayah'],
        ]);
    }
}
