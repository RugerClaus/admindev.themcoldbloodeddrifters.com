<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactMessages;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            ContactMessages::create([
                'name' => "User $i",
                'subject' => $i % 2 === 0 ? 'inquiry' : 'book',
                'phone' => '555-1234' . $i,
                'body' => "This is a test message number $i.",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
