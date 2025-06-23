<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Filters\FilterBuilder;
use Helper;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;
    
    protected $primaryKey = 'user_id';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\UserCreated::class,
        'updated' => \App\Events\UserUpdated::class,
        'deleted' => \App\Events\UserDeleted::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    public function profile()
    {
        return $this->morphTo();
    }
    
    /**
     * Get All Lines of Operator
     */
    public function operatorLines()
    {
        return $this->hasMany(Line::class,'operator_id');
    }
    /**
     * Get All Lines of Operator
     */
    public function accountManagerLines()
    {
        return $this->hasMany(Line::class,'account_manager_id');
    }

    /**
     * Get All Lines Of the Studio/Client
     */
    public function clientLines()
    {
        return $this->hasMany(Line::class,'client_id');
    }
    /**
     * Get Companies for the Client
     */
    public function companies()
    {
        return $this->hasMany(Company::class,'user_id');
    }

    /**
     * Get Line Income Monthly Pricing
     */
    public function lineIncomesMonthly()
    {
        return $this->hasMany(LineIncomeMonthly::class,'user_id','user_id');
    }
    /**
     * Get Line Expense Monthly Pricing
     */
    public function lineExpensesMonthly()
    {
        return $this->hasMany(LineExpenseMonthly::class,'user_id','user_id');
    }
    
    /*
    *  Get assigned Operators to given manager.
    */
    public function assignedOperators()
    {
        if($this->isAccountManager())
        {
            return $this->belongsToMany(User::class, ManagerOperatorAssignment::class, 'account_manager_id', 'operator_id')->orderBy('name','asc')->withTimestamps();
        }
        elseif($this->isClient())
        {
            return $this->belongsToMany(User::class,OperatorClientAssignment::class,'client_id','operator_id')->orderBy('name','asc')->withTimestamps();
        }
    }

    public function assignedClients()
    {
        if($this->isOperator())
        {
            return $this->belongsToMany(User::class,OperatorClientAssignment::class,'operator_id','client_id')->orderBy('name','asc')->withTimestamps();
        }
        elseif($this->isAccountManager())
        {
            return $this->belongsToMany(User::class,ManagerClientAssignment::class,'account_manager_id','client_id')->select(['users.*','manager_client_assignments.is_primary'])->orderBy('name','asc')->withTimestamps();
        }
    }

    public function assignedAccountManagers()
    {
        if($this->isOperator())
        {
            return $this->belongsToMany(User::class, ManagerOperatorAssignment::class,'operator_id','account_manager_id')->orderBy('name','asc')->withTimestamps(); 
        }
        elseif($this->isClient())
        {
            return $this->belongsToMany(User::class,ManagerClientAssignment::class,'client_id','account_manager_id')->select(['users.*','manager_client_assignments.is_primary'])->orderBy('name','asc')->withTimestamps();
        }
    }
    /**
     * Scope a query to only include all Admins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */ 
    public function scopeSuperAdmin($query)
    {
        $query->where('role_id', config('constant.ROLE_SUPER_ADMIN_ID'))->orderBy('name','asc');
    }

    /**
     * Scope a query to only include account manager.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeAccountManager($query)
    {
        $query->where('role_id', config('constant.ROLE_ACCOUNT_MANAGER_ID'))->orderBy('name','asc');
    }

    /**
     * Scope a query to only include operator users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeOperator($query)
    {
        $query->where('role_id', config('constant.ROLE_OPERATOR_ID'))->orderBy('name','asc');
    }    

    /**
     * Scope a query to only include client users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeClient($query)
    {
        $query->where('role_id', config('constant.ROLE_CLIENT_ID'))->orderBy('name','asc');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('status', 'active');
    }

    /**
     * Get the user's photo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::getProfileImg($value),
        );
    }
    /**
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
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::DateFormat($value),
        );
    }
    
    /**
     * Check if user is super admin
     * @return boolean
     */

    public function isSuperAdmin()
    {
        return Helper::isSuperAdmin($this->role_id);
    }

    /**
     * Check if user is account manager
     * @return boolean
     */

    public function isAccountManager()
    {
        return Helper::isAccountManager($this->role_id);
    }

    /**
     * Check if user is client
     * @return boolean
     */

    public function isClient(){
        return Helper::isClient($this->role_id);
    }

    /**
     * Check if user is operator
     * @return boolean
     */
    public function isOperator(){
        return Helper::isOperator($this->role_id);
    }

    public function saveUser(Request $request, User $user)
    {
        $user = $user->fill($request->all());
        
        if ($request->hasFile('photo')){
            $file_path = Helper::uploadFile($request->file('photo'),config ('constant.USER_PROFILE_PATH'));
            if($file_path){
                $user->photo = $file_path;
            }
        }
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->input("password"));
        }        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if($user->save()) 
            return $user;
        else
            return false;
    }
    public function upsertMonthlyPricing($startDate=null,$endtDate=null,$updateLines=false){
        Artisan::call("monthly-pricing:upsert",[
            "--user_id"=>$this->user_id,
            "--startdate"=>$startDate,
            "--enddate"=>$endtDate,
            "--update_lines"=>$updateLines
        ]);
    }
  
    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Filters\UserFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
