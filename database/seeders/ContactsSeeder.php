<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contacts')->insert([
            [
                'name' => 'Raj Sharma',
                'mobile' => '9782954150',
                'email' => 'raj9447@gmail.com',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'age' => '20',
                'salary' => '8591',
                'gender' => 'Female',
                'status' => 'Active',
                'created_at' => now(),
            ],
            [
                'name' => 'John Smith',
                'mobile' => '9690888158',
                'email' => 'john9691@gmail.com',
                'city' => 'Lucknow',
                'state' => 'Rajasthan',
                'age' => '23',
                'salary' => '32374',
                'gender' => 'Male',
                'status' => 'Inactive',
                'created_at' => now(),
            ],
            [
                'name' => 'John Singh',
                'mobile' => '9169429584',
                'email' => 'john1527@gmail.com',
                'city' => 'Indore',
                'state' => 'Gujarat',
                'age' => '26',
                'salary' => '15336',
                'gender' => 'Male',
                'status' => 'Active',
                'created_at' => now(),
            ],
        ]);
    }
}
