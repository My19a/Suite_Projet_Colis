<?php
$titre = 'Réception des colis – Postal Université';
$actif = '/postal-univ/reception';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Réception d'un colis</h1>
            <p class="page-subtitle">Enregistrer un colis reçu a l'université avant transfert vers l'IUT</p>
        </div>
    </div>

    <div class="bloc">
        <div class="formulaire">
            <form method="post" action="/postal-univ/reception" enctype="multipart/form-data">

                <div class="champ">
                    <label class="etiquette">Numéro de suivi</label>
                    <input type="text" name="numero_suivi" class="saisie" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Numéro de bon de commande</label>
                    <input type="text" name="numero_commande" class="saisie" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Photo de l'étiquette (optionnel)</label>
                    <input type="file" name="photo_etiquette" accept="image/*" class="saisie">
                </div>

                <div class="formulaire-info">
                    <p>Le campus / IUT sera identifié automatiquement via le bon de commande.</p>
                    <p>Si l'identification echoue, le colis sera marque <strong>Non identifié</strong>.</p>
                </div>

                <button type="submit" class="bouton bouton-principal">Enregistrer le colis</button>

            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
