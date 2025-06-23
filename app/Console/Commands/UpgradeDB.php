<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use App\Models\Line;
use App\Models\LineExpenseMonthly;
use App\Models\LineIncomeMonthly;
use App\Models\AccountManagerProfile;
use App\Models\OperatorProfile;

class UpgradeDB extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'upgrade:db';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update all lines in the lines table and set values for the new column';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$lines = Line::select('line_incomes_monthly.pricing_type', 'lines.*', 'companies.company_type')
				->leftJoin('companies', 'companies.company_id', '=', 'lines.company_id')
				->leftJoin('line_incomes_monthly', function (JoinClause $join) {
					$join->on('line_incomes_monthly.user_id', '=', 'lines.client_id')
						 ->on(DB::raw('MONTH(pricing_date)'), '=', DB::raw('MONTH(register_date)'))
						 ->on(DB::raw('YEAR(pricing_date)'), '=', DB::raw('YEAR(register_date)'));
				})
				->orderBy('lines.created_at')
				->orderBy('lines.register_date')
				->get();

		foreach ($lines as $line) {
			$line->line_type = $line->company_type;
			$line->line_pricing_type = $line->pricing_type;
			$line->save();
		}
		LineExpenseMonthly::where('pricing_type','!=','salary_plus_bonus')->update(['price'=>0]);
		LineIncomeMonthly::where('pricing_type','!=','fixed_price_with_milestone')->update(['price'=>0]);
		LineExpenseMonthly::where('id','>','0')->update(['sync_total'=>1]);
		LineIncomeMonthly::where('id','>','0')->update(['sync_total'=>1]);
		LineExpenseMonthly::where('id','>','0')->update(['sync_total'=>1]);
	}
}
