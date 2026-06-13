<?php
$titre = 'Ajouter un département – Admin';
$actif = '/admin/departements';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Ajouter un département</h1>
            <p class="page-subtitle">Créer un nouveau département</p>
        </div>
    </div>

    <div class="bloc">
        <div class="formulaire">
            <form method="post" action="/admin/ajouter-departement">

                <div class="champ">
                    <label class="etiquette">Nom du département</label>
                    <input type="text" name="nom" class="saisie" placeholder="Ex: Informatique" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Budget total (EUR)</label>
                    <input type="number" name="budget_total" class="saisie" placeholder="Ex: 50000" step="0.01" required>
                </div>

                <div class="formulaire-boutons">
                    <button type="submit" class="bouton bouton-principal">Créer le département</button>
                    <a href="/admin/departements" class="bouton bouton-secondaire">Annuler</a>
                </div>
            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
