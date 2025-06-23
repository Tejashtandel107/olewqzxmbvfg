<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Country;
use App\Models\ClientProfile;
use Helper;

class ProfileController extends Controller
{
        /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $Client = Auth::user();
        $home_url = Helper::getUserHomeURL($Client);
        $data = [
            'pagetitle'=>__('My Account'),
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                __('My Account') => ''
            ],
        ];
        $country = Country::get()->pluck('country_name','country_id')->toArray();

        $data['user'] = $Client;
        return view('client.profile.edit', $data)->with(compact('country'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request,User $user,ClientProfile $client_profile)
    {
        $user = $user->saveUser($request, $request->user());
        if ($user) {
			$client_profile->saveProfile($request,$user);
        }
        return Redirect::route('client.profile.edit')->with('status', 'profile-updated');
    }
}
