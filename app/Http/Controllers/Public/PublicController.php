<?php

namespace App\Http\Controllers\Public;

use App\Common\CommonPublicView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactRequest;
use App\Models\Contact\Contact;
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

    public function storeContact(ContactRequest $contactRequest)
    {        // Store in database
        Contact::create($contactRequest->validated());

        // Redirect with success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }


}