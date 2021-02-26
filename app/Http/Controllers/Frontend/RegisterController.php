<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;


class RegisterController extends Controller
{
    public function index () 
    {
        $params = ['title' => 'Register'];
        return view('frontend.register', $params);
    }


    public function process (Request $req) 
    {
        $this->validate($req, [
            'email' => 'required|email|max:250',
            'name' => 'required|max:250',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'email' => $req->email,
            'name' => $req->name,
            'password' => Hash::make($req->password),
        ]);

        return redirect()->route('login')->with('message', 'Now you can login');
    }
}
