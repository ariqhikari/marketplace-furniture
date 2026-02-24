<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@furnishop.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
            'phone' => '081234567890',
            'address' => 'Jl. Dipatiukur No. 1',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40132',
        ]);

        // Seller 1
        User::create([
            'name' => 'Toko Mebel Jaya',
            'email' => 'seller1@furnishop.com',
            'password' => Hash::make('password'),
            'role' => 'SELLER',
            'phone' => '081234567891',
            'store_name' => 'Mebel Jaya Abadi',
            'store_description' => 'Toko furniture berkualitas dengan bahan kayu jati pilihan.',
            'address' => 'Jl. Cihampelas No. 10',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40131',
        ]);

        // Seller 2
        User::create([
            'name' => 'Modern Living',
            'email' => 'seller2@furnishop.com',
            'password' => Hash::make('password'),
            'role' => 'SELLER',
            'phone' => '081234567892',
            'store_name' => 'Modern Living Store',
            'store_description' => 'Furniture minimalis modern untuk hunian masa kini.',
            'address' => 'Jl. Dago No. 25',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40135',
        ]);

        // Buyer 1
        User::create([
            'name' => 'Ariq Hikari Hidayat',
            'email' => 'buyer1@furnishop.com',
            'password' => Hash::make('password'),
            'role' => 'USER',
            'phone' => '081234567893',
            'address' => 'Jl. Buah Batu No. 5',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40265',
        ]);

        // Buyer 2
        User::create([
            'name' => 'Dewa Tri Wijaya',
            'email' => 'buyer2@furnishop.com',
            'password' => Hash::make('password'),
            'role' => 'USER',
            'phone' => '081234567894',
            'address' => 'Jl. Setiabudi No. 15',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40154',
        ]);
    }
}
