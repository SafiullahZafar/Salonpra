<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SalonSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Safiullah',
            'email' => 'admin@starline.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Categories
        $hair = Category::create(['name' => 'Hair Style', 'slug' => 'hair-style', 'type' => 'service']);
        $skin = Category::create(['name' => 'Skin Care', 'slug' => 'skin-care', 'type' => 'service']);
        $makeup = Category::create(['name' => 'Makeup', 'slug' => 'makeup', 'type' => 'service']);

        // Services
        $services = [
            ['name' => 'Bridal Hair Cut', 'price' => 1500, 'category_id' => $hair->id, 'duration' => 60, 'is_popular' => true, 'barcode' => 'H001'],
            ['name' => 'Professional Facial', 'price' => 3000, 'category_id' => $skin->id, 'duration' => 45, 'is_popular' => true, 'barcode' => 'S001'],
            ['name' => 'Bridal Makeup', 'price' => 15000, 'category_id' => $makeup->id, 'duration' => 120, 'is_popular' => true, 'barcode' => 'M001'],
            ['name' => 'Hair Styling', 'price' => 800, 'category_id' => $hair->id, 'duration' => 30, 'is_popular' => false, 'barcode' => 'H002'],
            ['name' => 'Skin Glow Masque', 'price' => 1200, 'category_id' => $skin->id, 'duration' => 20, 'is_popular' => false, 'barcode' => 'S002'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create 700 Customers (Fast)
        $customers = [];
        for ($i = 1; $i <= 700; $i++) {
            $customers[] = [
                'name' => "Customer $i",
                'phone' => "0300" . str_pad($i, 7, '0', STR_PAD_LEFT),
                'email' => "customer$i@example.com",
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now(),
            ];
            
            if ($i % 100 == 0) {
                Customer::insert($customers);
                $customers = [];
            }
        }

        // Create Appointments & Invoices to reach the target amounts
        // We need ~$56,500 total revenue
        for ($i = 0; $i < 50; $i++) {
            $amt = rand(500, 5000);
            Invoice::create([
                'invoice_no' => 'INV-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'user_id' => 1,
                'customer_id' => rand(1, 700),
                'total_amount' => $amt,
                'payable_amount' => $amt,
                'payment_method' => 'cash',
                'status' => 'paid',
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
        
        // Add one big invoice to boost it if needed
        Invoice::create([
            'invoice_no' => 'INV-TARGET',
            'user_id' => 1,
            'customer_id' => 1,
            'total_amount' => 50000,
            'payable_amount' => 50000,
            'payment_method' => 'card',
            'status' => 'paid',
        ]);

        // Create some appointments
        for ($i = 0; $i < 20; $i++) {
            Appointment::create([
                'customer_id' => rand(1, 700),
                'user_id' => 1,
                'service_id' => rand(1, 5),
                'appointment_date' => now()->addDays(rand(-5, 5)),
                'start_time' => '14:00',
                'end_time' => '15:00',
                'customer_name' => 'Walking Customer',
                'status' => 'scheduled',
            ]);
        }
    }
}
