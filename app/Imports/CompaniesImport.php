<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;
use Helper;

class CompaniesImport implements ToModel,SkipsEmptyRows, WithValidation, WithHeadingRow
{
    use Importable, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = User::client()->where("name",$row["client"])->orderByDesc('created_at')->first();
        $count = Company::where('user_id', $user->user_id)->where('company_name', $row['company'])->count();
        
        if($count>0)
            return;

        return new Company([
            'user_id'  => $user->user_id,
            'company_name' => $row['company'],
            'company_type' => $row['company_type'],
        ]);        
    }
    public function rules(): array
    {
        $client_role_id = config('constant.ROLE_CLIENT_ID');
        $company_types = Helper::getCompanyTypes();

        return [
             '*.client' => ['required',Rule::exists('users','name')->where('role_id', $client_role_id)],
             '*.company' => ['required'],
             '*.company_type' => ['required',Rule::in($company_types)],
        ];
    }
    
    public function batchSize(): int
    {
        return 500;
    }
}
