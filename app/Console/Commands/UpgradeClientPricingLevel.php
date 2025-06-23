<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ClientPriceLevel;
use App\Models\ClientProfile;
use App\Models\User;
use App\Services\LineClientService;
use Carbon\Carbon;

class UpgradeClientPricingLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client-pricing-level:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update clients pricing level based on completed tasks.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lineClientService = new LineClientService;
        $currentDate = Carbon::now();
        $clients = User::with('profile')->client()->active()->get();

        $pricingLevels = ClientPriceLevel::orderBy('order','asc')->get();     

        foreach($clients as $client){
            if(empty($client->profile->activation_date))
                continue;

            $activatonStartDate = $client->profile->activation_date;
            $activatonEndDate = ($client->profile->activation_date)->addYear()->setTime(23,59,59);
            $basePriceLevel = $pricingLevels->where('price_level_id',$client->profile->base_price_level_id)->first();

            $filters = [
                'from_date' => $activatonStartDate->format(config('constant.DATE_FORMAT')),
                'to_date' => $activatonEndDate->format(config('constant.DATE_FORMAT')),
                "client_id" => $client->user_id
            ];
          
            $results =  $lineClientService->getLineTotalsByClient($filters);
            $totals = $lineClientService->calculateTotals($results);
            $newLevel = $this->getNewLevel($pricingLevels,$totals->total_lines);

            if($activatonEndDate->gte($currentDate)){
                if($newLevel->order >= $basePriceLevel->order){
                    $data = [
                        'price_ordinaria'=> $newLevel->price_ordinaria,
                        'price_semplificata'=> $newLevel->price_semplificata,
                        'price_corrispettivi_semplificata'=> $newLevel->price_corrispettivi_semplificata,
                        'price_paghe_semplificata'=> $newLevel->price_paghe_semplificata,
                        'price_level_id'=>$newLevel->price_level_id,
                    ];
                    $this->updateClientProfile($client,$data);
                }
                else{
                    $data = [
                        'price_ordinaria'=> $basePriceLevel->price_ordinaria,
                        'price_semplificata'=> $basePriceLevel->price_semplificata,
                        'price_corrispettivi_semplificata'=> $basePriceLevel->price_corrispettivi_semplificata,
                        'price_paghe_semplificata'=> $basePriceLevel->price_paghe_semplificata,
                        'price_level_id'=>$basePriceLevel->price_level_id,
                    ];
                    $this->updateClientProfile($client,$data);
                }    
            }
            else{
                $data = [
                    'price_ordinaria'=> $newLevel->price_ordinaria,
                    'price_semplificata'=> $newLevel->price_semplificata,
                    'price_corrispettivi_semplificata'=> $newLevel->price_corrispettivi_semplificata,
                    'price_paghe_semplificata'=> $newLevel->price_paghe_semplificata,
                    'price_level_id'=>$newLevel->price_level_id,
                    'base_price_level_id'=>$newLevel->price_level_id,
                    'activation_date'=>$activatonEndDate
                ];
                $this->updateClientProfile($client,$data);
            }
        }
        
        return Command::SUCCESS;
    }

    public function getNewLevel(Collection $pricingLevels, int $totalLines=0):ClientPriceLevel{
        foreach($pricingLevels as $priceLevel){
            if($totalLines >= $priceLevel->min && $totalLines <= $priceLevel->max){
                return $priceLevel;
            }
        }
    }
    public function updateClientProfile($client,$data){
        ClientProfile::where('id','=',$client->profile_id)->update($data);
        $client->upsertMonthlyPricing();                    
    }
}
