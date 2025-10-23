<?php

namespace App\Http\Controllers\Access;

use App\Common\AffectGroupUserView;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupUserController extends Controller
{
    public function index()
    {
        return view(AffectGroupUserView::getListView());
    }
}
