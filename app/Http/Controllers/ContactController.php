<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('shop.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'nom'     => 'required|string|max:100',
            'email'   => 'required|email:rfc,dns',
            'message' => 'required|string|min:10|max:2000',
        ]);

        Log::info('Contact Blac Joyaux', $request->only('nom', 'email', 'sujet', 'message'));

        return back()->with('success', 'Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.');
    }
}