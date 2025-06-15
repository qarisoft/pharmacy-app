<?php

namespace Database\Seeders;

use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Salah',
            'email' => 'salah@t.t',
            'is_admin' => true,
        ]);
        User::factory()->create([
            'name' => 'naseem',
            'email' => 'naseem@t.t',
        ]);
        User::factory()->create([
            'name' => 'hadeel',
            'email' => 'hadeel@t.t',
        ]);



        $this->call(MeasureUnitNameSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(MeasureUnitSeeder::class);
        $this->call(ProductInputSeeder::class);
        $this->call(SaleItemSeeder::class);

    }
}
