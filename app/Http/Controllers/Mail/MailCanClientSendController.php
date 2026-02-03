<?php

namespace App\Http\Controllers\Mail;

use App\Common\MailAdminView;
use App\Http\Controllers\Controller;
use App\Models\Mail\MailCanClientSend;
use Illuminate\Http\Request;

class MailCanClientSendController extends Controller
{
    public function index()
    {
        // two different queries to optimize performance could be done here
        // admin and client queries are different
        $mailCanClientSends = MailCanClientSend::orderBy('created_at', 'desc')->paginate(20);
        return view(MailAdminView::getListView(), compact('mailCanClientSends'));
    }

    public function create()
    {        
        return view(MailAdminView::getCreateOrEditView());
    }
    public function show(MailCanClientSend $mailCanClientSend)
    {
        return view(MailAdminView::getShowView(), [
            'mailCanClientSend' => MailCanClientSend::findOrFail($mailCanClientSend->id),
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
