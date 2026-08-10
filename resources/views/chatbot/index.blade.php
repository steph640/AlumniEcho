@extends('template')
@section('title', 'Chatbot Echo - AlumniEcho')

@section('styles')
<style>
    .chat-wrapper { display: grid; grid-template-columns: 1fr 380px; gap: 20px; height: 70vh; min-height: 500px; }
    .chat-box { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(76,59,127,.1); display: flex; flex-direction: column; overflow: hidden; }
    .chat-header { background: linear-gradient(135deg, #4C3B7F, #6B5BA8); color: white; padding: 16px 20px; }
    .chat-header h5 { margin: 0; font-weight: 700; }
    .messages { flex: 1; overflow-y: auto; padding: 16px; background: #f8f7fc; }
    .message { margin-bottom: 14px; display: flex; flex-direction: column; max-width: 80%; }
    .message.user { align-items: flex-end; margin-left: auto; }
    .message.bot { align-items: flex-start; }
    .bubble { padding: 10px 14px; border-radius: 14px; font-size: 14px; line-height: 1.5; }
    .user .bubble { background: linear-gradient(135deg, #4C3B7F, #6B5BA8); color: white; border-bottom-right-radius: 4px; }
    .bot .bubble { background: white; color: #333; border: 1px solid #e0d8f5; border-bottom-left-radius: 4px; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
    .timestamp { font-size: 11px; color: #aaa; margin-top: 4px; padding: 0 4px; }
    .chat-input { padding: 12px 16px; border-top: 1px solid #eee; display: flex; gap: 8px; }
    .chat-input input { flex: 1; border: 1px solid #ddd; border-radius: 24px; padding: 8px 16px; font-size: 14px; outline: none; }
    .chat-input input:focus { border-color: #6B5BA8; }
    .chat-input button { background: linear-gradient(135deg, #4C3B7F, #6B5BA8); color: white; border: none; border-radius: 24px; padding: 8px 18px; font-size: 14px; cursor: pointer; }
    .faq-box { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(76,59,127,.1); display: flex; flex-direction: column; overflow: hidden; }
    .faq-header { background: linear-gradient(135deg, #2D5016, #4A7C2D); color: white; padding: 16px 20px; }
    .faq-header h5 { margin: 0; font-weight: 700; }
    .faq-list { flex: 1; overflow-y: auto; padding: 12px; }
    .faq-item { margin-bottom: 8px; border-radius: 8px; border: 1px solid #e0d8f5; overflow: hidden; cursor: pointer; }
    .faq-q { padding: 10px 14px; font-weight: 600; font-size: 13px; color: #4C3B7F; display: flex; justify-content: space-between; align-items: center; }
    .faq-q:hover { background: #f5f3fb; }
    .faq-a { padding: 10px 14px; font-size: 13px; color: #555; background: #faf9fe; border-top: 1px solid #e0d8f5; display: none; }
    .faq-item.open .faq-a { display: block; }
    .faq-item.open .faq-q { background: #f0ecfb; }
    .typing { display: flex; gap: 4px; align-items: center; padding: 10px 14px; }
    .typing span { width: 7px; height: 7px; background: #6B5BA8; border-radius: 50%; animation: bounce 1.2s infinite; }
    .typing span:nth-child(2) { animation-delay: .2s; }
    .typing span:nth-child(3) { animation-delay: .4s; }
    @keyframes bounce { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-6px)} }
    @media(max-width:768px){ .chat-wrapper{grid-template-columns:1fr; height:auto;} .chat-box{height:60vh;} .faq-box{height:300px;} }
.bot.error .bubble { background: #fff5f6; border-color: #f8d7da; color: #9a1f2b; }
</style>
@endsection

@section('main')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0"><i class="bi bi-robot me-2 text-primary"></i>Echo — Assistant AlumniEcho</h3>
        <p class="text-muted small mb-0">Posez vos questions ou consultez la FAQ</p>
    </div>
</div>

<div class="chat-wrapper">
    {{-- Zone de chat --}}
    <div class="chat-box">
        <div class="chat-header">
            <h5><i class="bi bi-chat-dots me-2"></i>Conversation</h5>
        </div>
        <div class="messages" id="messages"></div>
        <div class="chat-input">
            <input type="text" id="msgInput" placeholder="Posez votre question..." autocomplete="off">
            <button onclick="sendMessage()"><i class="bi bi-send"></i></button>
        </div>
    </div>

    {{-- FAQ --}}
    <div class="faq-box">
        <div class="faq-header">
            <h5><i class="bi bi-question-circle me-2"></i>Questions Fréquentes</h5>
        </div>
        <div class="faq-list" id="faqList">
            @forelse($faqs as $faq)
            <div class="faq-item" onclick="toggleFaq(this, '{{ addslashes($faq->question_faq) }}', '{{ addslashes($faq->reponse_faq) }}')">
                <div class="faq-q">
                    <span>{{ $faq->question_faq }}</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-a">{{ $faq->reponse_faq }}</div>
            </div>
            @empty
            <p class="text-muted text-center py-3">Aucune FAQ disponible.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const messagesDiv = document.getElementById('messages');
const msgInput = document.getElementById('msgInput');
const STORAGE_KEY = 'alumni_chat_history_v1';
let isSendingMessage = false;

function now() {
    return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
}

function addMessage(text, type) {
    addMessageWithSave(text, type, true);
}

function addMessageWithSave(text, type, save=true) {
    const div = document.createElement('div');
    div.className = `message ${type}`;
    div.innerHTML = `<div class="bubble">${text}</div><div class="timestamp">${now()}</div>`;
    messagesDiv.appendChild(div);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;

    if (save) {
        try {
            const raw = localStorage.getItem(STORAGE_KEY) || '[]';
            const arr = JSON.parse(raw);
            const last = arr[arr.length - 1];
            if (!last || last.text !== text || last.type !== type) {
                arr.push({ text: text, type: type, at: new Date().toISOString() });
            }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(arr.slice(-200)));
        } catch (e) {
            console.warn('Failed to save chat history', e);
        }
    }
}

function loadHistory() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return;
        const arr = JSON.parse(raw);
        arr.forEach(m => addMessageWithSave(m.text, m.type, false));
    } catch (e) {
        console.warn('Failed to load chat history', e);
    }
}

function showTyping() {
    const div = document.createElement('div');
    div.className = 'message bot';
    div.id = 'typing';
    div.innerHTML = `<div class="bubble typing"><span></span><span></span><span></span></div>`;
    messagesDiv.appendChild(div);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function removeTyping() {
    const t = document.getElementById('typing');
    if (t) t.remove();
}

function toggleFaq(el, question, answer) {
    el.classList.toggle('open');
    const icon = el.querySelector('.bi');
    icon.className = el.classList.contains('open') ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    if (el.classList.contains('open')) {
        addMessage(question, 'user');
        setTimeout(() => addMessage(answer, 'bot'), 400);
    }
}

async function sendMessage() {
    if (isSendingMessage) return;

    const msg = msgInput.value.trim();
    if (!msg) return;

    isSendingMessage = true;
    addMessage(msg, 'user');
    msgInput.value = '';
    showTyping();

    // Recherche dans les FAQs côté client
    const faqs = document.querySelectorAll('.faq-item');
    let found = null;
    const msgLower = msg.toLowerCase();
    faqs.forEach(item => {
        const q = item.querySelector('.faq-q span')?.textContent.toLowerCase() || '';
        const a = item.querySelector('.faq-a')?.textContent.toLowerCase() || '';
        if ((q.includes(msgLower) || a.includes(msgLower) || msgLower.includes(q.split(' ')[0])) && !found) {
            found = item.querySelector('.faq-a')?.textContent;
        }
    });

    if (found) {
        removeTyping();
        addMessage(found, 'bot');
        isSendingMessage = false;
        return;
    }

    try {
        const response = await fetch('/api/chatbot/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ question: msg }),
        });

        const data = await response.json();
        removeTyping();
        if (response.ok && data.reponse) {
            addMessage(data.reponse, 'bot');
        } else if (data.error) {
            addMessage('Erreur: ' + (data.error.message || data.error || 'Erreur lors de la requête.'), 'bot error');
        } else {
            addMessage('Erreur lors de la requête.', 'bot error');
        }
    } catch (error) {
        removeTyping();
        addMessage('Erreur de communication avec le serveur.', 'bot error');
    } finally {
        isSendingMessage = false;
    }
}

msgInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

// Message de bienvenue
window.addEventListener('DOMContentLoaded', () => {
    loadHistory();
    if (!localStorage.getItem(STORAGE_KEY)) {
        setTimeout(() => addMessage('Bonjour ! 👋 Je suis <strong>Echo</strong>, votre assistant AlumniEcho. Comment puis-je vous aider ?', 'bot'), 400);
    }
});
</script>
@endsection
