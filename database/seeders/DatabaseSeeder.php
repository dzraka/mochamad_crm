<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Lead;
use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\Customer;
use App\Models\CustomerService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $manager = User::create([
            'name' => 'Manager CRM',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        $sales1 = User::create([
            'name' => 'Andi Salesman',
            'email' => 'andi@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);

        $sales2 = User::create([
            'name' => 'Budi Salesman',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);

        $salesUsers = collect([$sales1, $sales2]);

        $productData = [
            ['name' => 'Paket Home 10 Mbps',    'description' => 'Internet rumah 10 Mbps',  'cost_price' => 100000, 'margin_percent' => 30],
            ['name' => 'Paket Home 25 Mbps',    'description' => 'Internet rumah 25 Mbps',  'cost_price' => 200000, 'margin_percent' => 25],
            ['name' => 'Paket Home 50 Mbps',    'description' => 'Internet rumah 50 Mbps',  'cost_price' => 350000, 'margin_percent' => 20],
            ['name' => 'Paket Bisnis 100 Mbps', 'description' => 'Internet bisnis 100 Mbps', 'cost_price' => 750000, 'margin_percent' => 20],
            ['name' => 'Paket Bisnis 200 Mbps', 'description' => 'Internet bisnis 200 Mbps', 'cost_price' => 1200000, 'margin_percent' => 15],
        ];

        $products = collect();
        foreach ($productData as $pd) {
            $products->push(Product::create($pd));
        }

        foreach ($salesUsers as $sales) {
            for ($i = 0; $i < 15; $i++) {
                $status = $faker->randomElement(['new', 'contacted', 'converted', 'rejected']);
                $createdAt = $faker->dateTimeBetween('-5 months', 'now');
                $isCompany = $faker->boolean(40);
                if ($isCompany) {
                    $name = $faker->company;
                } else {
                    $name = $faker->name;
                }

                $lead = Lead::create([
                    'user_id' => $sales->id,
                    'name' => $name,
                    'contact' => $faker->phoneNumber,
                    'address' => $faker->address,
                    'notes' => ($isCompany ? 'Kebutuhan B2B: ' : 'Kebutuhan Retail: ') . $faker->sentence,
                    'status' => $status,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                if (in_array($status, ['converted', 'contacted'])) {
                    $projectStatus = $status === 'converted' ? 'approved' : $faker->randomElement(['waiting_approval', 'rejected']);

                    $project = Project::create([
                        'lead_id' => $lead->id,
                        'user_id' => $sales->id,
                        'status' => $projectStatus,
                        'total_price' => 0,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    $selectedProducts = $products->random(rand(1, 3));
                    $totalPrice = 0;

                    foreach ($selectedProducts as $prod) {
                        $qty = rand(1, 5);
                        $negotiatedPrice = $prod->selling_price - rand(0, 50000);
                        $subtotal = $negotiatedPrice * $qty;
                        $totalPrice += $subtotal;

                        ProjectItem::create([
                            'project_id' => $project->id,
                            'product_id' => $prod->id,
                            'normal_price' => $prod->selling_price,
                            'negotiated_price' => $negotiatedPrice,
                            'qty' => $qty,
                            'subtotal' => $subtotal,
                        ]);
                    }

                    $project->update(['total_price' => $totalPrice]);

                    if ($projectStatus === 'approved') {
                        $customer = Customer::create([
                            'lead_id' => $lead->id,
                            'project_id' => $project->id,
                            'user_id' => $sales->id,
                            'name' => $lead->name,
                            'contact' => $lead->contact,
                            'address' => $lead->address,
                            'status' => 'active',
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]);

                        foreach ($project->items as $item) {
                            CustomerService::create([
                                'customer_id' => $customer->id,
                                'product_id' => $item->product_id,
                                'subscription_price' => $item->negotiated_price,
                                'start_date' => $createdAt->format('Y-m-d'),
                                'status' => 'active',
                            ]);
                        }
                    }
                }
            }
        }
    }
}
