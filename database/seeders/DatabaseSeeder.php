<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Client::factory()->count(100)->create();
        Product::factory()->count(10000)->create();
    }
}
