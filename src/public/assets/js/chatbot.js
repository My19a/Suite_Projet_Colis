function toggleChat() {
    const chatBox = document.getElementById('chatbot-box');
    const toggleBtn = document.getElementById('chatbot-toggle');
    if (chatBox.style.display === 'none') {
        chatBox.style.display = 'flex';
        toggleBtn.style.display = 'none';
    } else {
        chatBox.style.display = 'none';
        toggleBtn.style.display = 'block';
    }
}

async function envoyerMessage() {
    const input = document.getElementById('chatbot-input');
    const messageText = input.value.trim();
    if (!messageText) return;

    // Affiche le message de l'utilisateur
    ajouterMessage(messageText, 'user');
    input.value = '';

    // Affiche l'indicateur d'écriture
    const zoneMessages = document.getElementById('chatbot-messages');
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'message bot';
    loadingDiv.id = 'chatbot-loading';
    loadingDiv.innerText = 'Recherche en cours...';
    zoneMessages.appendChild(loadingDiv);
    zoneMessages.scrollTop = zoneMessages.scrollHeight;

    try {
        // On appelle directement index.php avec un paramètre classique, Apache ne pourra pas le bloquer !
const response = await fetch('/api-chatbot.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message: messageText })
});
        const data = await response.json();
        
        loadingDiv.remove();
        ajouterMessage(data.reponse, 'bot');
    } catch (error) {
        loadingDiv.remove();
        ajouterMessage('Une erreur de connexion est survenue. Veuillez réessayer.', 'bot');
    }
}

function ajouterMessage(texte, auteur) {
    const zoneMessages = document.getElementById('chatbot-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${auteur}`;
    messageDiv.innerText = texte;
    zoneMessages.appendChild(messageDiv);
    zoneMessages.scrollTop = zoneMessages.scrollHeight;
}