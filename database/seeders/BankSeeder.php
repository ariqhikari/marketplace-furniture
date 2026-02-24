<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

/**
 * BankSeeder — data rekening bank untuk pembayaran transfer
 */
class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'bank_name'      => 'BCA',
                'account_number' => '1234567890',
                'account_holder' => 'FurniShop Store',
                'is_active'      => true,
            ],
            [
                'bank_name'      => 'BNI',
                'account_number' => '0987654321',
                'account_holder' => 'FurniShop Store',
                'is_active'      => true,
            ],
            [
                'bank_name'      => 'BRI',
                'account_number' => '1122334455',
                'account_holder' => 'FurniShop Store',
                'is_active'      => true,
            ],
            [
                'bank_name'      => 'Mandiri',
                'account_number' => '5566778899',
                'account_holder' => 'FurniShop Store',
                'is_active'      => true,
            ],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
