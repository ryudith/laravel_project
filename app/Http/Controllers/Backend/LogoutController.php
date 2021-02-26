<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function process () 
    {
        auth()->logout();

        return redirect()->route('home');
    }
}
