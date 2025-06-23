<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ClientPriceLevel;

class ClientProfile extends Model
{
    use HasFactory;
    protected $casts = [
        'activation_date' => 'date:Y-m-d'
    ];

    protected $fillable=[
        'pricing_type',
        'price',
        'price_ordinaria',
        'price_semplificata',
        'price_corrispettivi_semplificata',
        'price_paghe_semplificata',
        'milestone',
        'vat_number',
        'activation_date',
        'price_level_id',
        'base_price_level_id',
        'address',
        'city',
        'province',
        'postal_code',
        'country_id',
        'additional_emails'
    ];

    public function user()
    {
        return $this->morphOne(User::class,'profile');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','country_id');
    }
    public function setActivationDateAttribute($value){
        if($value) {
            $this->attributes['activation_date'] = Carbon::createFromFormat('d/m/Y', "01/" . $value)->startOfMonth();
        } 
        else {
            $this->attributes['activation_date'] = null;
        }
    }
    protected function additionalEmails(): Attribute
    {
        return Attribute::make(
            get: function ($value): array {
                if(empty($value))      
                    return array();
                else
                    return explode(',',$value);
            }
        );
    }

    public function saveProfile(Request $request, $client){
        if($request->filled('activation_date') && $request->filled('price_level_id')){
           $clientPriceLevel =  ClientPriceLevel::find($request->price_level_id);
           $request->merge([
                'price_ordinaria' => $clientPriceLevel->price_ordinaria,
                'price_semplificata' => $clientPriceLevel->price_semplificata,
                'price_corrispettivi_semplificata' => $clientPriceLevel->price_corrispettivi_semplificata,
                'price_paghe_semplificata' => $clientPriceLevel->price_paghe_semplificata,
            ]); 
        }
       
        if(empty($client->profile)){
            $client_profile = self::create($request->all());
            $client_profile->user()->save($client);
        }
        else{
            $client->profile->fill($request->all())->save();
        }
    }
}
