<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\LineIncomeMonthly;
use App\Models\LineExpenseMonthly;
use DateTimeImmutable;

class UpsertMonthlyPricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly-pricing:upsert {--user_id=} {--startdate=} {--enddate=} {--update_lines}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or Update user pricings and re-calcuate lines total';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $startDate = $this->getDateTime($this->option('startdate'), 'start');
            $endDate = $this->getDateTime($this->option('enddate'), 'end');
            $updateLines = $this->option('update_lines');

            $query = User::where('role_id', '!=', config('constant.ROLE_SUPER_ADMIN_ID'));

            if (!empty($this->option('user_id'))) {
                $query->where("user_id", $this->option('user_id'));
            } 
            $users = $query->active()->get();

            foreach ($users as $user) {
                $this->upsertIncomesMonthly($user, $startDate, $endDate, $updateLines);
                $this->upsertExpensesMonthly($user, $startDate, $endDate, $updateLines);
            }

            $this->info("Monthly incomes and expenses pricings updated successfully.");

            return Command::SUCCESS;
        } catch (\Exception $error) {
            $this->error("Sorry, the system failed to update pricings: " . $error->getMessage());
            return Command::FAILURE;
        }
    }

    private function upsertIncomesMonthly(User $user, $startDate, $endDate, $updateLines = false): void
    {
        if (!$user->isClient()) {
            return;
        }

        $profile = $user->profile;
        $newStartDate = $startDate;
        $incomesMonthly = [];
        while ($endDate >= $newStartDate) {
            $price = ($profile->pricing_type == 'fixed_price' || $profile->pricing_type == 'fixed_price_with_milestone') ? $profile->price : 0;
            $incomesMonthly[] = [
                'user_id' => $user->user_id,
                'pricing_type' => $profile->pricing_type,
                'pricing_date' => $newStartDate->format('Y-m-d'),
                'price' => $price,
                'price_ordinaria' => $profile->price_ordinaria,
                'price_semplificata' => $profile->price_semplificata,
                'price_corrispettivi_semplificata' => $profile->price_corrispettivi_semplificata,
                'price_paghe_semplificata' => $profile->price_paghe_semplificata,
                'milestone' => $profile->milestone,
                'sync_total' => 1,
            ];
            $newStartDate = $newStartDate->modify("+1 month");
        }
        LineIncomeMonthly::upsert($incomesMonthly, ['pricing_date', 'user_id'], ['user_id', 'pricing_type', 'pricing_date', 'price', 'price_ordinaria', 'price_semplificata', 'price_corrispettivi_semplificata', 'price_paghe_semplificata', 'milestone', 'sync_total']);

        if ($updateLines) {
            //Get All Lines
            $lines = $user->clientLines()->whereBetween('register_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();
            //Re-calculate lines total
            foreach ($lines as $line) {
                if ($line->line_pricing_type != $profile->pricing_type) {
                    $line->line_pricing_type = ($profile->pricing_type) ?? null;
                    $line->save();
                }
            }
        }
    }

    private function upsertExpensesMonthly(User $user, $startDate, $endDate, $updateLines = false): void
    {
        if (!$user->isOperator() and !$user->isAccountManager()) {
            return;
        }

        $profile = $user->profile;
        $newStartDate = $startDate;
        $expensesMonthly = [];
        while ($endDate >= $newStartDate) {
            $price = ($profile->pricing_type == 'salary_plus_bonus') ? $profile->price : 0;
            $expensesMonthly[] = [
                'user_id' => $user->user_id,
                'pricing_type' => $profile->pricing_type,
                'pricing_date' => $newStartDate->format('Y-m-d'),
                'price' => $price,
                'price_righe_ordinaria' => $profile->price_righe_ordinaria,
                'price_righe_semplificata' => $profile->price_righe_semplificata,
                'price_righe_corrispettivi_semplificata' => $profile->price_righe_corrispettivi_semplificata,
                'price_righe_paghe_semplificata' => $profile->price_righe_paghe_semplificata,
                'price_righe_am_ordinaria' => isset($profile->price_righe_am_ordinaria) ? $profile->price_righe_am_ordinaria : '0.00',
                'price_righe_am_semplificata' => isset($profile->price_righe_am_semplificata) ? $profile->price_righe_am_semplificata : '0.00',
                'price_righe_am_corrispettivi_semplificata' => isset($profile->price_righe_am_corrispettivi_semplificata) ? $profile->price_righe_am_corrispettivi_semplificata : '0.00',
                'price_righe_am_paghe_semplificata' => isset($profile->price_righe_am_paghe_semplificata) ?  $profile->price_righe_am_paghe_semplificata : '0.00',
                'bonus_target' => $profile->bonus_target,
                'sync_total' => 1,
            ];
            $newStartDate = $newStartDate->modify("+1 month");
        }
        LineExpenseMonthly::upsert($expensesMonthly, ['pricing_date', 'user_id'], ['user_id', 'pricing_type', 'pricing_date', 'price', 'price_righe_ordinaria', 'price_righe_semplificata', 'price_righe_corrispettivi_semplificata', 'price_righe_paghe_semplificata', 'price_righe_am_ordinaria', 'price_righe_am_semplificata', 'price_righe_am_corrispettivi_semplificata', 'price_righe_am_paghe_semplificata', 'bonus_target', 'sync_total']);
    }

    /**
     * Get the date argument to the appropriate start or end of the month.
     */
    private function getDateTime(?string $date, string $type): DateTimeImmutable
    {
        if (empty($date)) {
            $dateTime = new DateTimeImmutable();
        } else {
            $dateTime = DateTimeImmutable::createFromFormat(config('constant.DATE_FORMAT'), $date);
        }

        return $type === 'start' ? $dateTime->modify('first day of this month') : $dateTime->modify('last day of this month');
    }
}
