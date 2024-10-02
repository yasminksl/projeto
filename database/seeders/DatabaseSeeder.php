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
        User::factory()->create([
            'name' => 'Yasmin',
            'email' => 'yasmin@yasmin.com',
            'password' => 'yasmin123'
        ]);

        Client::factory(10)->create();

        Product::factory(10)->create();

        Order::factory(50)->create();

        OrderHistory::factory(10)->create();
    }
}
