@extends('layouts.shop')

@section('title', 'Contact — Blac Joyaux')
@section('meta_description', 'Contactez Blac Joyaux par WhatsApp ou via notre formulaire. Showroom à Cocody Riviera Palmeraie, Abidjan.')

@section('content')
<section class="contact-page">
<div class="container">

    <h1 class="contact-title">Contactez-nous</h1>
    <div class="contact-underline"></div>

    <div class="contact-grid">

        {{-- COLONNE GAUCHE : Formulaire --}}
        <div class="contact-form-card">
            <h2>Envoyez un message</h2>

            <form action="{{ route('contact.send') }}" method="POST">
                @csrf

                <div class="contact-row">
                    <div class="contact-field">
                        <label for="nom">Nom complet</label>
                        <input type="text" id="nom" name="nom" placeholder="Ex: Jean Dupont" value="{{ old('nom') }}">
                        @error('nom') <span class="contact-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="contact-field">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="jean@example.com" value="{{ old('email') }}">
                        @error('email') <span class="contact-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-field">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="+225 00 00 00 00 00" value="{{ old('telephone') }}">
                    </div>
                    <div class="contact-field">
                        <label for="sujet">Sujet</label>
                        <select id="sujet" name="sujet">
                            <option value="information" {{ old('sujet') == 'information' ? 'selected' : '' }}>Demande d'information</option>
                            <option value="commande" {{ old('sujet') == 'commande' ? 'selected' : '' }}>Suivi de commande</option>
                            <option value="partenariat" {{ old('sujet') == 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                            <option value="autre" {{ old('sujet') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>

                <div class="contact-field">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" placeholder="Comment pouvons-nous vous aider ?">{{ old('message') }}</textarea>
                    @error('message') <span class="contact-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-contact-send">Envoyer le message</button>
            </form>
        </div>

        {{-- COLONNE DROITE : Infos --}}
        <div class="contact-side">

            <div class="contact-whatsapp-list">
                @foreach([
                    ['label' => 'WhatsApp Direct', 'number' => config('services.brand.whatsapp')],
                    ['label' => 'WhatsApp', 'number' => '2250719161037'],
                ] as $line)
                <a href="https://wa.me/{{ $line['number'] }}?text=Bonjour%20Blac%20Joyaux%2C%20je%20voudrais%20avoir%20des%20informations."
                   target="_blank" rel="noopener" class="contact-whatsapp">
                    <div class="contact-whatsapp-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.5 2 2 6.5 2 12c0 1.8.5 3.5 1.3 5L2 22l5.2-1.3c1.4.8 3.1 1.3 4.8 1.3 5.5 0 10-4.5 10-10S17.5 2 12 2zm5.8 14.2c-.2.7-1.4 1.3-2 1.4-.5.1-1.2.1-1.9-.1-.4-.1-1-.3-1.7-.7-3-1.3-4.9-4.3-5.1-4.5-.1-.2-1.2-1.6-1.2-3.1s.8-2.2 1.1-2.5c.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .6.5.2.5.7 1.8.8 1.9.1.2.1.3 0 .5-.1.2-.1.3-.3.5-.1.2-.3.4-.4.5-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.5 1.5.3.1.5.1.7-.1.2-.2.8-.9 1-1.2.2-.3.4-.2.7-.1.3.1 1.7.8 2 .9.3.1.5.2.6.3.1.2.1.9-.1 1.6z"/></svg>
                        <div>
                            <div class="label">{{ $line['label'] }}</div>
                            <div class="value">+{{ $line['number'] }}</div>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
                @endforeach
            </div>

            <div class="contact-infos">
                <div class="contact-info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <div>
                        <div class="label">Adresse</div>
                        <div class="value">Rond-point de la Riviera Palmeraie<br>Cocody, Abidjan — Côte d'Ivoire</div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <div>
                        <div class="label">Horaires</div>
                        <div class="value">Lundi – Samedi : 09h00 – 18h00</div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <div>
                        <div class="label">Contact / SAV</div>
                        <div class="value">+225 07 08 77 15 57<br>+225 05 45 45 22 15</div>
                    </div>
                </div>
            </div>

            <div class="contact-social">
                <span>Suivez-nous</span>
                <div class="contact-social-icons">
                    <a href="#" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.87v-6.99H7.9v-2.88h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.88h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="https://wa.me/{{ config('services.brand.whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
</section>

@endsection