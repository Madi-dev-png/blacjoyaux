{{-- Assistant IA Blac Joyaux — flotte sur toutes les pages --}}
<div id="bj-chat" class="bj-chat" data-endpoint="{{ route('chat.send') }}" data-csrf="{{ csrf_token() }}">
    <button id="bj-chat-toggle" class="bj-chat-toggle" aria-label="Ouvrir l'assistant Blac Joyaux">
        <span class="bj-chat-toggle-ico">✦</span>
        <strong>Assistante Blac Joyaux</strong>
    </button>

    <div id="bj-chat-panel" class="bj-chat-panel" hidden>
        <div class="bj-chat-header">
            <div>
                <strong>Assistante Blac Joyaux</strong>
                <span class="bj-chat-status">En ligne · répond en quelques secondes</span>
            </div>
            <button id="bj-chat-close" class="bj-chat-close" aria-label="Fermer">✕</button>
        </div>

        <div id="bj-chat-messages" class="bj-chat-messages">
            <div class="bj-msg bj-msg-bot">
                Bonjour 👋 Je suis l'assistante de Blac Joyaux. Posez-moi vos questions sur nos sacs,
                la livraison, le paiement ou notre histoire Ashanti. Comment puis-je vous aider ?
            </div>
            <div class="bj-chat-suggestions">
                <button class="bj-suggestion">Quels sacs avez-vous ?</button>
                <button class="bj-suggestion">Délais de livraison ?</button>
                <button class="bj-suggestion">Comment payer ?</button>
            </div>
        </div>

        <form id="bj-chat-form" class="bj-chat-form">
            <input id="bj-chat-input" type="text" placeholder="Écrivez votre message…" autocomplete="off" maxlength="1000" required>
            <button type="submit" aria-label="Envoyer">➤</button>
        </form>
    </div>
</div>

@push('styles')
<style>
.bj-chat { position: fixed; bottom: 1.4rem; right: 1.4rem; z-index: 80; font-family: var(--font-body); }
.bj-chat-toggle {
    display: flex; align-items: center; gap: .6rem;
    background: var(--aubergine); color: var(--ivoire);
    border: none; padding: .85rem 1.3rem; border-radius: 100px;
    font-size: .95rem; font-weight: 500; cursor: pointer;
    box-shadow: var(--shadow); transition: transform .15s, background .2s;
}
.bj-chat-toggle:hover { transform: translateY(-2px); background: var(--aubergine-2); }
.bj-chat-toggle-ico { color: var(--or-clair); font-size: 1.1rem; }

.bj-chat-panel {
    position: absolute; bottom: 0; right: 0;
    width: min(380px, 90vw); height: min(560px, 78vh);
    background: var(--ivoire); border-radius: var(--r-lg);
    box-shadow: var(--shadow); display: flex; flex-direction: column; overflow: hidden;
    border: 1px solid var(--ivoire-2);
}
.bj-chat-header {
    background: var(--aubergine); color: var(--ivoire);
    padding: 1rem 1.2rem; display: flex; justify-content: space-between; align-items: flex-start;
}
.bj-chat-header strong { font-family: var(--font-display); font-size: 1.05rem; display: block; }
.bj-chat-status { font-size: .72rem; opacity: .75; }
.bj-chat-close { background: none; border: none; color: var(--ivoire); font-size: 1rem; cursor: pointer; opacity: .8; }
.bj-chat-close:hover { opacity: 1; }

.bj-chat-messages { flex: 1; overflow-y: auto; padding: 1.2rem; display: flex; flex-direction: column; gap: .8rem; }
.bj-msg { max-width: 85%; padding: .7rem 1rem; border-radius: var(--r-md); font-size: .9rem; line-height: 1.5; white-space: pre-wrap; }
.bj-msg-bot { background: var(--blanc); color: var(--encre); align-self: flex-start; border: 1px solid var(--ivoire-2); border-bottom-left-radius: 4px; }
.bj-msg-user { background: var(--or); color: var(--encre); align-self: flex-end; border-bottom-right-radius: 4px; }
.bj-msg-typing { align-self: flex-start; color: var(--gris); font-size: .85rem; font-style: italic; }

.bj-chat-suggestions { display: flex; flex-wrap: wrap; gap: .4rem; }
.bj-suggestion {
    background: var(--blanc); border: 1px solid var(--ivoire-2); color: var(--aubergine);
    font-size: .8rem; padding: .4rem .8rem; border-radius: 100px; cursor: pointer; font-family: var(--font-body);
}
.bj-suggestion:hover { background: var(--ivoire-2); }

.bj-chat-form { display: flex; gap: .5rem; padding: .9rem; border-top: 1px solid var(--ivoire-2); background: var(--blanc); }
.bj-chat-form input { flex: 1; padding: .7rem .9rem; border: 1px solid var(--ivoire-2); border-radius: 100px; font-family: var(--font-body); font-size: .9rem; }
.bj-chat-form button { background: var(--aubergine); color: var(--ivoire); border: none; width: 42px; border-radius: 50%; cursor: pointer; font-size: .9rem; }
.bj-chat-form button:hover { background: var(--aubergine-2); }
</style>
@endpush
