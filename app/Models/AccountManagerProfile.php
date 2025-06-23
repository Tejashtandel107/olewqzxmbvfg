<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AccountManagerProfile extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'pricing_type',
        'price',
        'price_righe_ordinaria',
        'price_righe_semplificata',
        'price_righe_corrispettivi_semplificata',
        'price_righe_paghe_semplificata',
        'bonus_target',
    ];

    public function user()
    {
        return $this->morphOne(User::class,'profile');
    }

    public function saveProfile(Request $request, $account_manager){
        if(empty($account_manager->profile)){
            $account_manager_profile = self::create($request->all());
            $account_manager_profile->user()->save($account_manager);
        }
        else{
            $account_manager->profile->fill($request->all())->save();
        }
    }
}
