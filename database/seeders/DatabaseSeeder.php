<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(10)->create();

        Client::factory(10)->create();

        Product::factory(20)->create();

        Order::factory(30)->create();

        OrderHistory::factory(50)->create();
    }
}
