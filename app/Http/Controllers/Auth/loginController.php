<?php

namespace App\Http\Controllers\Auth;

/*
 * We will access Laravel's authentication services via the Auth facade,
 so we'll need to make sure to import the Auth facade at the top of the class
*
*/
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class loginController extends Controller
{
    //constructor
    public function __construct()
    {
        // no one can access this functions except logout function
        $this->middleware('guest',['except'=>'logout']);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        if(!Auth::attempt(['userable_id'=>$request->ID ,'password'=>$request->password] , $request->remember))
        {
            $request->flash();
           return back()->withErrors([
                'AuthProblem'=>'Wrong ID or Password',
           ]);
        }
        // finish login completely
        return redirect()->intended('/home');

    }
    public function logout()
    {
        Auth::logout();
        return redirect()->home();
    }
}
