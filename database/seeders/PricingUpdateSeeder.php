<?php

namespace Database\Seeders;

use App\Models\AccountManagerProfile;
use App\Models\ClientProfile;
use App\Models\LineExpenseMonthly;
use App\Models\LineIncomeMonthly;
use App\Models\OperatorProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Line;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class PricingUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Line::where("line_id",">",0)->update(['payment_register_type' => 'Corrispettivi']);

        LineIncomeMonthly::where("id",">",0)->update(['price_paghe_semplificata' => 3]);
        LineExpenseMonthly::where("id",">",0)->update(['price_paghe_semplificata' => 0.45,'price_corrispettivi_semplificata' => 1.5]);

        ClientProfile::where("id",">",0)->update(['price_paghe_semplificata' => 3]);
        OperatorProfile::where("id",">",0)->update(['price_paghe_semplificata' => 0.45,'price_corrispettivi_semplificata' => 1.5]);
        AccountManagerProfile::where("id",">",0)->update(['price_paghe_semplificata' => 0.45,'price_corrispettivi_semplificata' => 1.5]);

        LineIncomeMonthly::where("id",">",0)->update(['total_lines_ordinaria' => DB::raw('(total_passive_lines_ordinaria + total_active_lines_ordinaria + total_corrispettivi_lines_ordinaria + total_primanota_lines_ordinaria)')]);
        LineIncomeMonthly::where("id",">",0)->update(['total_lines_semplificata' => DB::raw('(total_passive_lines_semplificata + total_active_lines_semplificata)')]);
        LineIncomeMonthly::where("id",">",0)->update(['total_cost' => DB::raw('(total_cost_fixed + total_cost_bonus)')]);
        
        $lines = Line::groupBy('operator_id',DB::raw('MONTH(register_date)'),DB::raw('YEAR(register_date)'))
                ->orderBy('line_id')
                ->get();
        foreach($lines as $line){
            $line->save();
        }
        
        Schema::table('line_incomes_monthly', function (Blueprint $table) {
            $table->dropColumn(['total_passive_lines_ordinaria', 'total_active_lines_ordinaria', 'total_corrispettivi_lines_ordinaria','total_primanota_lines_ordinaria','total_passive_lines_semplificata','total_active_lines_semplificata','total_cost_fixed','total_cost_bonus','total_cost_lines_ordinaria','total_cost_lines_semplificata']);
        });
    }
}
