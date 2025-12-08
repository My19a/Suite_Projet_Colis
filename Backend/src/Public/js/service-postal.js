// Fonction de recherche dans le tableau
document.getElementById('searchBC').addEventListener('input', function(e) {
    const searchValue = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.colis-table tbody tr');
    
    rows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Fonction pour transférer un colis
function transfererColis(idColis) {
    if (confirm('Confirmer le transfert de ce colis vers l\'IUT ?')) {
        // Envoie une requête au serveur
        fetch('index.php?route=service-postal-transferer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_colis: idColis })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                alert('Colis transféré avec succès !');
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            alert('Erreur lors du transfert');
        });
    }
}
