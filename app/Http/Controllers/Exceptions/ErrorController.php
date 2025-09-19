<?php

namespace App\Http\Controllers\Exceptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ErrorController extends Controller
{
    public function error401():View
    {
        return view('errors.401');
    }

    public function error500():View
    {
        return view('errors.500');
    }

    public function error404():View
    {
        return view('errors.404');
    }

    public function error403():View
    {
        return view('errors.403');
    }
}