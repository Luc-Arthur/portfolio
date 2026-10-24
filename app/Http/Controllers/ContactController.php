<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['prohibited'],
        ], [
            'website.prohibited' => 'Requête invalide.',
        ]);

        $recipient = Setting::first()?->email ?: config('mail.from.address');

        Mail::to($recipient)->send(new ContactMessage(
            name: $validated['name'],
            email: $validated['email'],
            subject: $validated['subject'] ?? null,
            body: $validated['message'],
        ));

        return redirect()
            ->route('contact')
            ->with('success', 'Votre message a bien été envoyé, merci !');
    }
}
