<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Company;
use App\Models\Line;
use App\Models\AdminProfile;
use App\Models\AccountManagerProfile;
use App\Models\ClientProfile;
use App\Models\OperatorProfile;
use Illuminate\Database\Seeder;
use Database\Seeders\BankSeeder;
use Database\Seeders\MonthSeeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BankSeeder::class,
        ]);

        //$user->createToken('outsourcing-app'); 
        User::factory(2)->create([
            'role_id' => config('constant.ROLE_SUPER_ADMIN_ID'),
        ])->each(function ($user){
            $profile = AdminProfile::factory()->create();
            $profile->user()->save($user);
        });
        
        //Account Manager
        User::factory(3)->create([
            'role_id' => config('constant.ROLE_ACCOUNT_MANAGER_ID'),
        ])->each(function ($user){
            $profile = AccountManagerProfile::factory()->create();
            $profile->user()->save($user);
        });

        //Operator
        User::factory(5)->create([
            'role_id' => config('constant.ROLE_OPERATOR_ID'),
        ])->each(function ($user){
            $profile = OperatorProfile::factory()->create();
            $profile->user()->save($user);
            $user->assignedAccountManagers()->sync([fake()->randomElement(User::accountmanager()->get()->pluck('user_id')->toArray())]);
        });

        //Client
        User::factory(10)
            ->has(
                Company::factory()->count(5),'companies'
            )
            ->create([
                'role_id' => config('constant.ROLE_CLIENT_ID'),
            ])
            ->each(function ($user){
                $profile = ClientProfile::factory()->create();
                $profile->user()->save($user);
        });
        /*
        //Line
        Line::factory(100)->create()
        */
    }
}
