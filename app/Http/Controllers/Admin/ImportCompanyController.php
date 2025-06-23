<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Imports\CompaniesImport;
 
class ImportCompanyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = [
			'pagetitle'=>__('Company Import'),
            "breadcrumbs"=>[
                trans_choice("Company|Companies",1) => route("admin.companies.index"),
                __('Company Import')=>"",
            ],
        ];
        return view('admin.company.import.create',$data);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'import_file' => ["required","file"],
        ]);

        if($request->wantsJson()){
            $import = new CompaniesImport();
            $import->import($request->file('import_file'));

            $type = "success";
            $message = __("Your file has been imported successfully.");                                                    
            return response()->json(array('message' => $message, 'type' => $type));
        }           
       
    }
}
