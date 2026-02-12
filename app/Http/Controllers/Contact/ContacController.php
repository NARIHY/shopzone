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
        Contact::findOrFail(303)->notify(new \App\Notifications\Client\Answer\MailClientAnsweerNotification(
            Contact::findOrFail(303),
            [
                'subject' => 'Test Notification',
                'content' => 'This is a test notification content.',
                'media_path' => 'mail'. DIRECTORY_SEPARATOR. 'media'. DIRECTORY_SEPARATOR. 'ReportingReport.xlsx',
                'sender_type' => 'company',
            ]
        ));
        return view(ContactAdminView::getListView());
    }
}
