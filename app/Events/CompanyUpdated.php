<?php

namespace App\Events;

use App\Models\Company;
use Illuminate\Broadcasting\InteractsWithSockets;
Use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanyUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $company;
    public $auth_user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->auth_user = Auth::user();
    }
}
