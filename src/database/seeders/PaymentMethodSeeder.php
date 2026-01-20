<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods =[
        [
            'display_name' => 'カード払い',
            'code' => 'credit_card',
            'stripe_method' => 'card',
        ],
        [
            'display_name' => 'コンビニ払い',
            'code' => 'konbini',
            'stripe_method' => 'konbini',
        ],
        ];   

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate($method);
        }
    }
}
