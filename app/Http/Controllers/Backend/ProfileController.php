<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    public function read () 
    {
        $user = auth()->user();

        $params = [
            'title' => 'Profile',
            'name' => $user->name,
            'email' => $user->email,
        ];
        return view('backend.profile.index', $params);
    }


    public function edit (Request $req) 
    {
        $rules = [
            'name' => 'required|max:250',
            'email' => 'required|email|max:250',
        ];

        if ($req->password) {
            $rules['password'] = 'required|confirmed';
        }

        $validator = Validator::make($req->all(), $rules);
        $validator->validate();

        $user = auth()->user();
        $user->name = $req->name;
        $user->email = $req->email;

        if ($req->password) {
            $user->password = Hash::make($req->password);
        }

        $user->save();

        return redirect()->route('profile')->with('message', 'Data updated');
    }
}
