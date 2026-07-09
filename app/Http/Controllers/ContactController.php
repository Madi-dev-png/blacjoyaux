<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('shop.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns',
            'telephone' => 'nullable|string|max:30',
            'sujet' => 'nullable|string|max:100',
            'message' => 'required|string|min:10|max:2000',
        ]);

        Log::info('Contact Blac Joyaux', $validated);

        try {
            Mail::to(config('services.brand.contact_email'))->send(new ContactMessage($validated));
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', "Votre message n'a pas pu être envoyé pour le moment. Merci de nous contacter directement via WhatsApp.");
        }

        return back()->with('success', 'Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.');
    }
}
