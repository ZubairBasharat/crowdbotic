<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['currency' => 'PKR'],
            ['currency' => 'USD'],
            ['currency' => 'RMB'],
            ['currency' => 'POUND'],
            ['currency' => 'EURO'],
            ['currency' => 'YEN'],
            ['currency' => 'SAR'],
            ['currency' => 'QAR'],
        ];

        foreach ($types as $role) {
            Currency::create($role);
        }
    }
}
