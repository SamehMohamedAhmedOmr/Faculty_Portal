<?php

namespace App\Http\Controllers;

use App\Experience;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function profile()
    {
        $user = Auth::user();
        $SpecializationAttibutes=$user->userable;
        return view('Portal.user.profile',compact('user','SpecializationAttibutes'));
    }
    public function update_profile(Request $request)
    {
        $this->validate($request, [
            'address'=>'required|max:100|regex:"(?=.*[A-Za-z])[A-Za-z0-9\'\.\-\s\,]{5,}"',
            'phone'=>'required|digits_between:8,13',
            'email'=>'required|max:100|email|unique:users,email,'.$request->id.',userable_id'
        ],
        [
            'address.required'  => 'Address is required',
            'phone.required'  => 'Phone number is required',
            'email.required'  => 'E-mail is required',
            'address.regex'  => 'Please enter valid address between 5 and 100 characters (contains letters)',
            'address.max'  => 'The address can not be more than 100 characters',
            'phone.digits_between'  => 'Please enter valid phone number',
            'email.email'  => 'Please enter valid E-mail',
            'email.max'  => 'Please enter valid E-mail (Maximum 100 characters)',
            'email.unique'  => 'This email is already exists',
        ]);
        $user = Auth::user();
        $user->email=$request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        if($user->save())
            return redirect()->action('userController@profile')->with('message', 'Profile updated successfully');
        else
            App::abort(500, 'Error');
    }
}