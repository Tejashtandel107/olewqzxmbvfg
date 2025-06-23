<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Helper;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminProfile>
 */
class AccountManagerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $pricing_types = array_keys(Helper::getAccountManagerPricingTypes());

        return [
            'pricing_type'          => fake()->randomElement($pricing_types),
            'price'                 => fake()->randomNumber(4,true),
            'price_ordinaria'       => 0.015,
            'price_semplificata'    => 0.015,
            'bonus_target'          => 4000,
            'created_at'            => now()
        ];
    }
}
