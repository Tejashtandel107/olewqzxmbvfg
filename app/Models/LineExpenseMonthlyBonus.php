<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Filters\FilterBuilder;

class LineExpenseMonthlyBonus extends Model
{
    protected $primaryKey = 'line_bonus_id';
    
    protected $fillable = [
        'source_user_id',
        'user_id',
        'pricing_date',
        'total_lines_ordinaria',
        'total_lines_semplificata',
        'total_corrispettivi_lines_semplificata',
        'total_paghe_lines_semplificata',
        'total_bonus_ordinaria',
        'total_bonus_semplificata',
        'total_bonus_corrispettivi_semplificata',
        'total_bonus_paghe_semplificata',
        'total_bonus',
        'total_lines',
    ];
    protected $casts = [
        'pricing_date' => 'date:Y-m-d',
    ];
    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Filters\LineExpenseMonthlyBonusFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
