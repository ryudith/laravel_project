<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lend;
use App\Models\Pay;


class DashboardController extends Controller
{
    public function index () 
    {
        $params = [
            'title' => 'Dashboard',
            'lends' => Lend::where([ ['data_owner', '=', auth()->user()->id], ])->orderBy('id', 'DESC')->limit(5)->get(),
            'pays' => Pay::where([ ['data_owner', '=', auth()->user()->id], ])->orderBy('id', 'DESC')->limit(5)->get(),
        ];
        return view('backend.dashboard.index', $params);
    }
}
