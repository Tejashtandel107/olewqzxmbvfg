<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Helper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => fake()->randomElement(User::client()->get()->pluck('user_id')->toArray()),
            'company_name' => fake()->company(),
            'company_type' => fake()->randomElement(Helper::getCompanyTypes()),
            'business_type' => fake()->randomElement(Helper::getBusinessTypes()),
            'vat_tax'=>fake()->phoneNumber(),
            'created_at' => now()
        ];
    }
}
