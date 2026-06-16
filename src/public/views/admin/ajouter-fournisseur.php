<?php
$titre = 'Ajouter un fournisseur – Admin';
$actif = '/admin/fournisseurs';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Ajouter un fournisseur</h1>
            <p class="page-subtitle">Créer un nouveau fournisseur</p>
        </div>
    </div>

    <div class="bloc">
        <div class="formulaire">
            <form method="post" action="/admin/ajouter-fournisseur">

                <div class="champs-en-ligne">
                    <div class="champ">
                        <label class="etiquette">Nom</label>
                        <input type="text" name="nom" class="saisie" required>
                    </div>
                    <div class="champ">
                        <label class="etiquette">Nom du contact</label>
                        <input type="text" name="contact_nom" class="saisie">
                    </div>
                </div>

                <div class="champs-en-ligne">
                    <div class="champ">
                        <label class="etiquette">Email</label>
                        <input type="email" name="contact_email" class="saisie">
                    </div>
                    <div class="champ">
                        <label class="etiquette">Téléphone</label>
                        <input type="text" name="contact_telephone" class="saisie">
                    </div>
                </div>

                <div class="formulaire-boutons">
                    <button type="submit" class="bouton bouton-principal">Créer le fournisseur</button>
                    <a href="/admin/fournisseurs" class="bouton bouton-secondaire">Annuler</a>
                </div>
            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
