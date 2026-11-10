<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            DrugSeeder::class,
            StockBatchSeeder::class,
            DispensingRecordSeeder::class,
        ]);
    }
}
