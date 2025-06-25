<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lead::create([
            'user_id' => 2, // Replace with a valid user ID
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',            
            'headline' => 'headlineg',
            'address' => 'address',
        ]);

        Lead::create([
            'user_id' => 2, // Replace with a valid user ID
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '0987654321',
            'headline' => 'headlineg',
            'address' => 'address',
        ]);

        // Add more sample data as needed
        Lead::create([
            'user_id' => 1, // Replace with a valid user ID
            'name' => 'Janeaa Smith',
            'email' => 'janea.smith@example.com',
            'phone' => '0987654321',
            'headline' => 'headlineg',
            'address' => 'address',
        ]);
    }
}
