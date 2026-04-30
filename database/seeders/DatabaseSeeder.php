<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Manager CRM',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);
        User::create([
            'name' => 'Andi Salesman',
            'email' => 'andi@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);
        User::create([
            'name' => 'Budi Salesman',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);

        $products = [
            ['name' => 'Paket Home 10 Mbps',    'description' => 'Internet rumah 10 Mbps',  'cost_price' => 100000, 'margin_percent' => 30],
            ['name' => 'Paket Home 25 Mbps',    'description' => 'Internet rumah 25 Mbps',  'cost_price' => 200000, 'margin_percent' => 25],
            ['name' => 'Paket Home 50 Mbps',    'description' => 'Internet rumah 50 Mbps',  'cost_price' => 350000, 'margin_percent' => 20],
            ['name' => 'Paket Bisnis 100 Mbps', 'description' => 'Internet bisnis 100 Mbps', 'cost_price' => 750000, 'margin_percent' => 20],
            ['name' => 'Paket Bisnis 200 Mbps', 'description' => 'Internet bisnis 200 Mbps', 'cost_price' => 1200000, 'margin_percent' => 15],
        ];
        foreach ($products as $product) {
            Product::create($product);
        }
    }

}
