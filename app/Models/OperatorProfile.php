<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OperatorProfile extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'pricing_type',
        'price',
        'price_righe_ordinaria',
        'price_righe_semplificata',
        'price_righe_corrispettivi_semplificata',
        'price_righe_paghe_semplificata',
        'price_righe_am_ordinaria',
        'price_righe_am_semplificata',
        'price_righe_am_corrispettivi_semplificata',
        'price_righe_am_paghe_semplificata',
        'bonus_target',
    ];

    public function user()
    {
        return $this->morphOne(User::class,'profile');
    }

    public function saveProfile(Request $request, $operator){
        if(empty($operator->profile)){
            $operator_profile = self::create($request->all());
            $operator_profile->user()->save($operator);
        }
        else{
            $operator->profile->fill($request->all())->save();
        }
    }
}
