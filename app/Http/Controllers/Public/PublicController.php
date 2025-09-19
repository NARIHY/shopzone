<?php

namespace App\Http\Controllers\Public;

use App\Common\CommonPublicView;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        return view(CommonPublicView::getHomeView());
    }

    public function about(): View
    {
        return view(CommonPublicView::getAboutView());
    }

    public function contact(): View
    {
        return view(CommonPublicView::getContactView());
    }


}