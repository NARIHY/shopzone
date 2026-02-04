<?php

namespace App\Http\Controllers\Mail;

use App\Common\MailAdminView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\MailCanClientSend\MailCanClientSendToCreateRequest;
use App\Jobs\Mail\MailCanSend\ProccessCreateMailCanSendJob;
use App\Models\Mail\MailCanClientSend;
use Illuminate\Http\Request;

class MailCanClientSendController extends Controller
{
    public function index()
    {
        return view(MailAdminView::getListView());
    }

    public function edit(MailCanClientSend $mailCanClientSend)
    {
        return view(MailAdminView::getCreateOrEditView(), [
            'mailCanClientSend' => MailCanClientSend::findOrFail($mailCanClientSend->id),
        ]);
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

    public function store(MailCanClientSendToCreateRequest $mailCanClientSendToCreateRequest)
    {
        ProccessCreateMailCanSendJob::dispatch($mailCanClientSendToCreateRequest->validated());
        return redirect()->route('admin.mailcanclientsend.index');
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
