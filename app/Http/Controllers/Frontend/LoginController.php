<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\UserLogin;


class LoginController extends Controller
{
    public function index () 
    {
        $params = ['title' => 'Login'];
        return view('frontend.login', $params);
    }


    public function process (Request $req) 
    {
        $this->validate($req, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! auth()->attempt($req->only('email', 'password'), $req->remember)) {
            return back()->with('error', 'Invalid email and password');
        }

        UserLogin::dispatch(auth()->user());

        return redirect()->route('dashboard');
    }
}
