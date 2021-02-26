<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $csrfTokenName = '_token';
    protected $csrfTokenValue;
    protected $baseUploadPath;
    protected $subUploadPath;
    protected $subUploadPayPath;


    public function init () 
    {
        $this->csrfTokenValue = csrf_token();
        $this->baseUploadPath = storage_path('app/public/');
        $this->subUploadPath = 'upload/lend/';
        $this->subUploadPayPath = 'upload/pay/';
    }
}
