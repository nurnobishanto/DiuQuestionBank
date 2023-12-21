<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            ['name' => 'Weekly Plan', 'price' => 10.99, 'duration' => 'week'],
            ['name' => 'Monthly Plan', 'price' => 29.99, 'duration' => 'month'],
            ['name' => 'Quarterly Plan', 'price' => 79.99, 'duration' => 'quarter'],
            ['name' => 'Half-Yearly Plan', 'price' => 149.99, 'duration' => 'half_year'],
            ['name' => 'Yearly Plan', 'price' => 249.99, 'duration' => 'year'],
            ['name' => 'Lifetime Plan', 'price' => 499.99, 'duration' => 'lifetime'],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
