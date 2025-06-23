<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminProfile>
 */
class AdminProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'notify_on_client_create'    => 1,
            'notify_on_client_update'    => 1,
            'notify_on_client_delete'    => 1,
            'notify_on_company_create'  => 1,
            'notify_on_company_update'  => 1,
            'notify_on_company_delete'  => 1,
            'created_at' => now()
        ];
    }
}
