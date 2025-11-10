<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuardController extends Controller
{
    public function unAuthorizeUsers() // ← CORRIGÉ : A majuscule + orthographe
    {
        return view('admin.errors.unhautorize-users');
    }
}
