<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        $subjectLabels = [
            'commande' => 'Une commande',
            'produit' => 'Un produit',
            'livraison' => 'Livraison',
            'partenariat' => 'Partenariat',
            'autre' => 'Autre',
        ];

        $sujet = $subjectLabels[$this->data['sujet'] ?? ''] ?? 'Nouveau message';

        return new Envelope(
            subject: '['.$sujet.'] Message de '.$this->data['nom'].' — Site Blac Joyaux',
            replyTo: [$this->data['email']],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
            with: ['data' => $this->data],
        );
    }
}
