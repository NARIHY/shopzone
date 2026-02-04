<?php

namespace App\Http\Controllers\Mail;

use App\Common\MailAdminView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\MailCanClientSend\MailCanClientSendToCreateRequest;
use App\Http\Requests\Mail\MailCanClientSend\MailCanClientSendToUpdateRequest;
use App\Jobs\Mail\MailCanSend\ProccessCreateMailCanSendJob;
use App\Jobs\Mail\MailCanSend\ProccessUpdateMailCanSendJob;
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
        // Do nothing, no show view
    }

    public function store(MailCanClientSendToCreateRequest $mailCanClientSendToCreateRequest)
    {
        ProccessCreateMailCanSendJob::dispatch($mailCanClientSendToCreateRequest->validated());
        return redirect()->route('admin.mailcanclientsend.index');
    }

    public function update(MailCanClientSendToUpdateRequest $mailCanClientSendToUpdateRequest, $id)
    {
        ProccessUpdateMailCanSendJob::dispatch($mailCanClientSendToUpdateRequest->validated(), MailCanClientSend::findOrFail($id));
        return redirect()->back();
    }
    public function destroy($id)
    {
        // do nothing cannot delete
    }
}
