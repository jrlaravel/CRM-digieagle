<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Adjust the model path if necessary
use Illuminate\Support\Facades\Hash;

class HRseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
            User::create([
                'first_name' => 'hemangi',
                'last_name' => 'kapadiya',
                'email' => 'HR@gmail.com',
                'username' => 'HR@123',
                'birth_date' => '2000-01-01',
                'empcode' => '0002',
                'phone' => '1234567890',
                'department' => '0',
                'designation' => '0',
                'skills' => [],
                'address' => '123 Admin Street',
                'password' => Hash::make('123456'), // Change 'password' to your desired default password
                'profile_photo_path' => null, // Or provide a path if you have a default profile picture
                'role' => 'hr', // Assuming you have a 'role' field to distinguish admin users
            ]);
    }
}
