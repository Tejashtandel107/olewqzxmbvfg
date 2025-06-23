<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Helper;

class ProfileController extends Controller
{
        /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $operator = Auth::user();
        $home_url = Helper::getUserHomeURL($operator);
        $data = [
            'pagetitle'=>__('My Account'),
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                __('My Account') => ''
            ],
        ];

        $data['user'] = $operator;
        return view('operator.profile.edit', $data);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request,User $user)
    {
        $user->saveUser($request, $request->user());
        return Redirect::route('operator.profile.edit')->with('status', 'profile-updated');
    }
}
