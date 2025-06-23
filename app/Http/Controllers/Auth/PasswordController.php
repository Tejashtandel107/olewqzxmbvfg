<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['required'],
        ]);
        
        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        if($request->wantsJson()){
            return response()->json(array('message' => trans('password.reset'), 'type' => "success"));
        }
        else{
            return back()->with('status', 'password-updated');
        }
    }
}
