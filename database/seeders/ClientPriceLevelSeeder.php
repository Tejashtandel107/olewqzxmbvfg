<?php

namespace Database\Seeders;

use App\Models\ClientPriceLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientPriceLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $levels = [
            ['price_level_id' => 1,'title'=>'Level 1','min'=>0,'max'=>10000,'price_ordinaria'=>0.40,'price_semplificata'=>0.50,'price_corrispettivi_semplificata'=>10.00,'price_paghe_semplificata'=>3,'order'=>1],
            ['price_level_id' => 2,'title'=>'Level 2','min'=>10001,'max'=>40000,'price_ordinaria'=>0.35,'price_semplificata'=>0.45,'price_corrispettivi_semplificata'=>10.00,'price_paghe_semplificata'=>3,'order'=>2],
            ['price_level_id' => 3,'title'=>'Level 3','min'=>40001,'max'=>100000,'price_ordinaria'=>0.30,'price_semplificata'=>0.40,'price_corrispettivi_semplificata'=>5.00,'price_paghe_semplificata'=>2.50,'order'=>3],
            ['price_level_id' => 4,'title'=>'Level 4','min'=>100000,'max'=>100000000,'price_ordinaria'=>0.25,'price_semplificata'=>0.35,'price_corrispettivi_semplificata'=>5.00,'price_paghe_semplificata'=>2.5,'order'=>4]
       ];
       ClientPriceLevel::insert($levels);
    }
}
