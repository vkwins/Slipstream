<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\CustomerCategory;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $goldCategory = CustomerCategory::where('name', 'Gold')->first();
        $silverCategory = CustomerCategory::where('name', 'Silver')->first();
        $bronzeCategory = CustomerCategory::where('name', 'Bronze')->first();

        // Create sample customers
        $customers = [
            [
                'name' => 'Acme Corporation',
                'reference' => 'ACME001',
                'customer_category_id' => $goldCategory->id,
                'start_date' => '2023-01-15',
                'description' => 'Leading technology solutions provider with global presence.'
            ],
            [
                'name' => 'TechStart Inc',
                'reference' => 'TECH002',
                'customer_category_id' => $silverCategory->id,
                'start_date' => '2023-03-20',
                'description' => 'Innovative startup focused on AI and machine learning.'
            ],
            [
                'name' => 'Global Solutions Ltd',
                'reference' => 'GLOB003',
                'customer_category_id' => $goldCategory->id,
                'start_date' => '2022-11-10',
                'description' => 'International consulting firm specializing in digital transformation.'
            ],
            [
                'name' => 'Local Business Co',
                'reference' => 'LOCAL004',
                'customer_category_id' => $bronzeCategory->id,
                'start_date' => '2023-06-05',
                'description' => 'Small local business providing excellent customer service.'
            ],
            [
                'name' => 'Enterprise Systems',
                'reference' => 'ENT005',
                'customer_category_id' => $goldCategory->id,
                'start_date' => '2022-08-12',
                'description' => 'Enterprise software solutions for large corporations.'
            ]
        ];

        foreach ($customers as $customerData) {
            $customer = Customer::create($customerData);

            // Add sample contacts for each customer
            $contacts = [
                [
                    'first_name' => 'John',
                    'last_name' => 'Smith'
                ],
                [
                    'first_name' => 'Sarah',
                    'last_name' => 'Johnson'
                ]
            ];

            foreach ($contacts as $contactData) {
                Contact::create([
                    'customer_id' => $customer->id,
                    'first_name' => $contactData['first_name'],
                    'last_name' => $contactData['last_name']
                ]);
            }
        }
    }
}
