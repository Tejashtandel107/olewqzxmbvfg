<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Filters\FilterBuilder;
use Helper;

class Company extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\CompanyCreated::class,
        'updated' => \App\Events\CompanyUpdated::class,
        'deleted' => \App\Events\CompanyDeleted::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'company_type',
        'vat_tax',
        'business_type',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('order_by', function (Builder $builder) {
            $builder->orderBy('company_name');
        });
    }

    /**
     * Get All Line Records for the Company
     */
    public function lines(){
        return $this->hasMany(Line::class,'company_id');
    }
    /**
     * Get the user that owns the company.
     */
    public function client()
    {  
        return $this->belongsTo(User::class,'user_id');  
    }
    /**
     * Get the pricing for the company.
     */
  
    /**
     * Get the item's created at.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::DateFormat($value),
        );
    }
    /**
     * Get the item's updated at.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::DateFormat($value),
        );
    }
    
    public function saveCompany($request, Company $company){
        $company = $company->fill($request->all());

        if($company->save()) 
            return $company;
        else
            return false;
    }
    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Filters\CompanyFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
