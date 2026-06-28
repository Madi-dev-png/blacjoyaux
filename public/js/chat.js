/* Assistant IA Blac Joyaux — logique du widget de chat */
(function () {
    const root = document.getElementById('bj-chat');
    if (!root) return;

    const endpoint = root.dataset.endpoint;
    const csrf = root.dataset.csrf;

    const toggle = document.getElementById('bj-chat-toggle');
    const panel = document.getElementById('bj-chat-panel');
    const closeBtn = document.getElementById('bj-chat-close');
    const messages = document.getElementById('bj-chat-messages');
    const form = document.getElementById('bj-chat-form');
    const input = document.getElementById('bj-chat-input');

    // Historique de la conversation (envoyé à l'API pour le contexte)
    let history = [];

    function openPanel() {
        panel.hidden = false;
        toggle.style.display = 'none';
        input.focus();
        scrollDown();
    }
    function closePanel() {
        panel.hidden = true;
        toggle.style.display = 'flex';
    }

    toggle.addEventListener('click', openPanel);
    closeBtn.addEventListener('click', closePanel);

    function scrollDown() {
        messages.scrollTop = messages.scrollHeight;
    }

    function addMessage(text, who) {
        const div = document.createElement('div');
        div.className = 'bj-msg bj-msg-' + who;
        div.textContent = text;
        messages.appendChild(div);
        scrollDown();
        return div;
    }

    function removeSuggestions() {
        const s = messages.querySelector('.bj-chat-suggestions');
        if (s) s.remove();
    }

    async function sendMessage(text) {
        removeSuggestions();
        addMessage(text, 'user');
        history.push({ role: 'user', content: text });
        input.value = '';

        const typing = document.createElement('div');
        typing.className = 'bj-msg bj-msg-typing';
        typing.textContent = 'L\'assistante écrit…';
        messages.appendChild(typing);
        scrollDown();

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text, history: history.slice(0, -1) }),
            });
            const data = await res.json();
            typing.remove();

            const reply = data.reply || "Désolée, je n'ai pas pu répondre. Réessayez ou contactez-nous sur WhatsApp.";
            addMessage(reply, 'bot');
            history.push({ role: 'assistant', content: reply });
        } catch (e) {
            typing.remove();
            addMessage("Une erreur est survenue. Merci de réessayer dans un instant.", 'bot');
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = input.value.trim();
        if (text) sendMessage(text);
    });

    // Suggestions cliquables
    messages.addEventListener('click', function (e) {
        if (e.target.classList.contains('bj-suggestion')) {
            sendMessage(e.target.textContent);
        }
    });
})();
