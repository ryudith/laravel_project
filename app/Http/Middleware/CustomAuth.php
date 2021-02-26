<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomAuth extends Authenticate
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $request->session()->flash('error', 'Please login to access page');
            
            return route('login');
        }
    }
}
