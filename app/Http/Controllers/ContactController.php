<?php

namespace App\Http\Controllers;

use App\Events\ContactFormSubmitted;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function submit(ContactRequest $request)
    {
        ContactFormSubmitted::dispatch(
            firstName: $request->validated('first_name'),
            lastName: $request->validated('last_name'),
            email: $request->validated('email'),
            company: $request->validated('company'),
            service: $request->validated('service'),
            message: $request->validated('message'),
        );

        return back()->with('success', 'Thank you for your message. We will be in touch shortly.');
    }
}
