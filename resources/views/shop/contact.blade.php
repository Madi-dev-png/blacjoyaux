@extends('layouts.shop')

@section('title', 'Contact — Blac Joyaux')
@section('meta_description', 'Contactez Blac Joyaux par WhatsApp ou via notre formulaire. Showroom à Cocody Palmeraie, Abidjan.')

@section('content')
<div class="container" style="padding: 3rem 1rem;">

    <h1 style="font-family: var(--font-display); font-size: 2.2rem; margin-bottom: .3rem;">Contactez-nous</h1>
    <div style="width: 60px; height: 3px; background: var(--encre); margin-bottom: 2.5rem;"></div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">

        {{-- COLONNE GAUCHE : Formulaire --}}
        <div style="background: var(--blanc); border: 1px solid var(--ivoire-2); border-radius: var(--r-md); padding: 2rem;">
            <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem;">Envoyez un message</h2>

            @if(session('success'))
                <div style="background: #d1fae5; color: #065f46; padding: .9rem 1rem; border-radius: var(--r-md); margin-bottom: 1.2rem;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="font-size: .85rem; font-weight: 500; display: block; margin-bottom: .4rem;">Nom complet</label>
                        <input type="text" name="nom" placeholder="Ex: Jean Dupont" value="{{ old('nom') }}"
                               style="width: 100%; padding: .7rem .9rem; border: 1px solid var(--ivoire-2); border-radius: var(--r-md); font-family: var(--font-body); font-size: .9rem; box-sizing: border-box;">
                        @error('nom') <span style="color:red; font-size:.8rem;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="font-size: .85rem; font-weight: 500; display: block; margin-bottom: .4rem;">E-mail</label>
                        <input type="email" name="email" placeholder="jean@example.com" value="{{ old('email') }}"
                               style="width: 100%; padding: .7rem .9rem; border: 1px solid var(--ivoire-2); border-radius: var(--r-md); font-family: var(--font-body); font-size: .9rem; box-sizing: border-box;">
                        @error('email') <span style="color:red; font-size:.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="font-size: .85rem; font-weight: 500; display: block; margin-bottom: .4rem;">Téléphone</label>
                        <input type="tel" name="telephone" placeholder="+225 00 00 00 00 00" value="{{ old('telephone') }}"
                               style="width: 100%; padding: .7rem .9rem; border: 1px solid var(--ivoire-2); border-radius: var(--r-md); font-family: var(--font-body); font-size: .9rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="font-size: .85rem; font-weight: 500; display: block; margin-bottom: .4rem;">Sujet</label>
                        <select name="sujet"
                                style="width: 100%; padding: .7rem .9rem; border: 1px solid var(--ivoire-2); border-radius: var(--r-md); font-family: var(--font-body); font-size: .9rem; box-sizing: border-box; background: white;">
                            <option value="information" {{ old('sujet') == 'information' ? 'selected' : '' }}>Demande d'information</option>
                            <option value="commande" {{ old('sujet') == 'commande' ? 'selected' : '' }}>Suivi de commande</option>
                            <option value="partenariat" {{ old('sujet') == 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                            <option value="autre" {{ old('sujet') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: .85rem; font-weight: 500; display: block; margin-bottom: .4rem;">Message</label>
                    <textarea name="message" rows="6" placeholder="Comment pouvons-nous vous aider ?"
                              style="width: 100%; padding: .7rem .9rem; border: 1px solid var(--ivoire-2); border-radius: var(--r-md); font-family: var(--font-body); font-size: .9rem; resize: vertical; box-sizing: border-box;">{{ old('message') }}</textarea>
                    @error('message') <span style="color:red; font-size:.8rem;">{{ $message }}</span> @enderror
                </div>

                <button type="submit"
                        style="width: 100%; background: var(--encre); color: white; padding: .95rem; border: none; border-radius: var(--r-md); font-family: var(--font-body); font-size: .95rem; font-weight: 600; cursor: pointer; letter-spacing: .05em; text-transform: uppercase;">
                    Envoyer le message
                </button>
            </form>
        </div>

        {{-- COLONNE DROITE : Infos --}}
        <div style="display: flex; flex-direction: column; gap: 1rem;">

            {{-- Carte / Localisation --}}
            <div style="background: var(--ivoire-2); border-radius: var(--r-md); height: 200px; overflow: hidden;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.0!2d-3.9833!3d5.3364!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sCocody+Palmeraie+Abidjan!5e0!3m2!1sfr!2sci!4v1"
                    width="100%" height="200" style="border:0;"
                    allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            {{-- WhatsApp --}}
            <a href="https://wa.me/{{ config('services.brand.whatsapp') }}?text=Bonjour%20Blac%20Joyaux%2C%20je%20voudrais%20avoir%20des%20informations."
               target="_blank" rel="noopener"
               style="background: var(--encre); color: white; border-radius: var(--r-md); padding: 1.2rem 1.5rem;
                      display: flex; align-items: center; justify-content: space-between; text-decoration: none;">
                <div style="display: flex; align-items: center; gap: .8rem;">
                    <span style="font-size: 1.4rem;">💬</span>
                    <div>
                        <div style="font-size: .75rem; opacity: .7; text-transform: uppercase; letter-spacing: .08em;">WhatsApp Direct</div>
                        <div style="font-size: 1.1rem; font-weight: 600;">+{{ config('services.brand.whatsapp') }}</div>
                    </div>
                </div>
                <span style="font-size: 1.3rem;">→</span>
            </a>

            {{-- Infos pratiques --}}
            <div style="background: var(--blanc); border: 1px solid var(--ivoire-2); border-radius: var(--r-md); padding: 1.4rem; display: flex; flex-direction: column; gap: 1.2rem;">
                <div style="display: flex; gap: .8rem; align-items: flex-start;">
                    <span>📍</span>
                    <div>
                        <div style="font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; margin-bottom: .2rem;">Adresse</div>
                        <div style="font-size: .9rem; color: var(--gris);">Cocody Palmeraie, Abidjan<br>Côte d'Ivoire</div>
                    </div>
                </div>
                <div style="display: flex; gap: .8rem; align-items: flex-start;">
                    <span>🕐</span>
                    <div>
                        <div style="font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; margin-bottom: .2rem;">Horaires</div>
                        <div style="font-size: .9rem; color: var(--gris);">Lun – Ven : 10h00 – 19h00<br>Sam : 11h00 – 18h00</div>
                    </div>
                </div>
                <div style="display: flex; gap: .8rem; align-items: flex-start;">
                    <span>🌐</span>
                    <div>
                        <div style="font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; margin-bottom: .2rem;">En ligne</div>
                        <div style="font-size: .9rem; color: var(--gris);">blacjoyaux.com<br>contact@blacjoyaux.com</div>
                    </div>
                </div>
            </div>

            {{-- Réseaux sociaux --}}
            <div style="background: var(--blanc); border: 1px solid var(--ivoire-2); border-radius: var(--r-md); padding: 1rem 1.4rem; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;">Suivez-nous</span>
                <div style="display: flex; gap: 1rem;">
                    <a href="#" style="color: var(--encre); font-size: 1.2rem; text-decoration: none;">▶</a>
                    <a href="#" style="color: var(--encre); font-size: 1.2rem; text-decoration: none;">📷</a>
                    <a href="#" style="color: var(--encre); font-size: 1.2rem; text-decoration: none;">✉</a>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
@media (max-width: 768px) {
    .contact-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush

@endsection