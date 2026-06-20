<div id="chatbot-wrapper">
    <button id="chatbot-toggle" onclick="toggleChat()">Suivi Colis & Aide</button>

    <div id="chatbot-box" style="display: none;">
        <div id="chatbot-header">
            <span>Assistant IA Colis</span>
            <button onclick="toggleChat()" style="background:none; border:none; color:white; cursor:pointer; font-size: 16px;">✖</button>
        </div>
        <div id="chatbot-messages">
            <div class="message bot">Bonjour ! Je suis votre assistant virtuel. Donnez-moi votre numéro de suivi pour savoir où en est votre colis.</div>
        </div>
        <div id="chatbot-input-area">
            <input type="text" id="chatbot-input" placeholder="Ex: Où est mon colis FR12345 ?" onkeypress="if(event.key === 'Enter') envoyerMessage()">
            <button onclick="envoyerMessage()">Envoyer</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/assets/css/chatbot.css?v=1.0">
<script src="/assets/js/chatbot.js?v=1.0"></script>