<?php

namespace App\Http\Controllers\Contact;

use App\Common\ContactAdminView;
use App\Http\Controllers\Controller;
use App\Models\Contact\Contact;
use Illuminate\Http\Request;

class ContacController extends Controller
{
    public function index()
    {
        return view(ContactAdminView::getCategoryListView(), [
            'contacts'=> Contact::orderBy('created_at','desc')->paginate(10)
        ]);
    }
}
