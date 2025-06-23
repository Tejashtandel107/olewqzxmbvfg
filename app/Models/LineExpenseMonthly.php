<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Filters\FilterBuilder;

class LineExpenseMonthly extends Model
{
    use HasFactory;
    protected $table = 'line_expenses_monthly';

    protected $fillable = [
        'user_id',
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
        'pricing_date',
        'sync_total'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pricing_date' => 'date:Y-m-d',
    ];

    /**
     * Get the user that owns the pricing.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function sourceUserLineExpenseBonuses():HasMany
    {
        return $this->HasMany(LineExpenseMonthlyBonus::class, 'source_user_id', 'user_id')->where('pricing_date',"=",$this->pricing_date);
    }      
     
    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Filters\LineExpenseMonthlyFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
