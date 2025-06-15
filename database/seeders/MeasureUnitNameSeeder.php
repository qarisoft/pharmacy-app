<?php

namespace Database\Seeders;

use App\Models\Products\Company;
use App\Models\Products\MeasureUnitName;
use App\Models\Products\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasureUnitNameSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MeasureUnitName::factory()->createMany([
            ['id'=>1,'name'=>'باكت'],
            ['id'=>2,'name'=>'امبولة'],
            ['id'=>3,'name'=>'قربة'],
            ['id'=>4,'name'=>'فيالة'],
            ['id'=>5,'name'=>'شريط'],
            ['id'=>6,'name'=>'زجاجة'],
            ['id'=>7,'name'=>'كرتون'],
            ['id'=>8,'name'=>'حبة'],
            ['id'=>9,'name'=>'شدة'],
            ['id'=>10,'name'=>'مغذية'],
            ['id'=>11,'name'=>'كيس'],
            ['id'=>12,'name'=>'تبخيرة'],
            ['id'=>13,'name'=>'مرهم'],
            ['id'=>14,'name'=>'حقنة'],
            ['id'=>15,'name'=>'قرص'],
            ['id'=>16,'name'=>'قطر'],
        ]);

    }
}
