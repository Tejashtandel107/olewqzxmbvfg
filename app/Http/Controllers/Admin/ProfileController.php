<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\SettingUpdateRequest;
use App\Models\Country;
use App\Models\Setting;
use App\Models\User;
use Helper;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $admin = Auth::user();
        $home_url = Helper::getUserHomeURL($admin);
        $data = [
            'pagetitle'=>__('My Account'),
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                __('My Account') => ''
            ],
        ];
        $settings = Setting::get()->pluck('option_value', 'option_name');
        $country = Country::get()->pluck('country_name','country_name')->toArray(); 
        $data['user'] = $admin;
        return view('admin.profile.edit', $data)->with(compact('country','settings'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request,User $user)
    {
        $user->saveUser($request, $request->user());
        $user=Auth::user();
        $notifications = ($request->filled('notifications')) ? $request->notifications : array();
        $user->profile->saveNotifications($notifications,$user);
        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    public function updateSettings(SettingUpdateRequest $request,Setting $setting)
    {
        $setting->saveSettings($request->except(['_token','_method']));
        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated-settings');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
