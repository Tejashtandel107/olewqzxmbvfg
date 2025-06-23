<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\User;
use App\Models\Bank;
use App\Models\Line;
use Helper;

class LineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $line = new Line;
        $randomDate = fake()->dateTimeThisYear();
        $client_id = fake()-> randomElement(User::client()->get()->pluck('user_id')->toArray());
        $operator_id = fake()->randomElement(User::where('role_id',[2,4])->get()->pluck('user_id')->toArray());
        $accountManagerId = $line->getAccountManagerByClientId($client_id);
        return [
            'client_id' => fake()-> randomElement(Company::get()->pluck('user_id')->toArray()),
            'company_id' => fake()->randomElement(Company::where("user_id",$client_id)->get()->pluck('company_id')->toArray()),
            'operator_id' => $operator_id,
            'account_manager_id' => ($accountManagerId) ?? null,
            'register_date' => $randomDate->format('Y-m-d'),
            'purchase_invoice_from' => fake()->numberBetween(20,40),
            'purchase_invoice_to' => fake()->numberBetween(50,90),
            'purchase_invoice_lines' => fake()->numberBetween(20,90),
            'purchase_invoice_registrations' => null,
            'sales_invoice_from' => fake()->numberBetween(20,40),
            'sales_invoice_to' => fake()->numberBetween(50,90),
            'sales_invoice_lines' => fake()->numberBetween(20,90),
            'sales_invoice_registrations' => null,
            'payment_register_month_id' => fake()->randomKey(Helper::getMonths()),
            'payment_register_day' => fake()->dayOfMonth(),
            'payment_register_daily_records' => fake()->randomDigit(20),
            'payment_register_lines' => fake()->randomDigit(20),
            'petty_cash_book_type' => "Banca",
            'petty_cash_bank_id' => fake()-> randomElement(Bank::get()->pluck('bank_id')->toArray()),
            'petty_cash_other_bank' => null,
            'petty_cash_book_month_id' => fake()->randomKey(Helper::getMonths()),
            'petty_cash_book_lines' => fake()->randomDigit(20),
            'time_spent_start_time' => $randomDate->format(config('constant.DATE_TIME_FORMAT')),
            'time_spent_end_time' => $randomDate->format(config('constant.DATE_TIME_FORMAT')),
            'overtime_extra' => fake()->randomFloat(1,1,3),
            'overtime_note' => fake()->sentence(10),
            'created_at'=>$randomDate
        ];
    }
}