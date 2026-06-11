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

    <div class="section">
        <div class="form-card">
            <form method="post" action="/postal-univ/reception" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="form-label">Numéro de suivi</label>
                    <input type="text" name="numero_suivi" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Numéro de bon de commande</label>
                    <input type="text" name="numero_commande" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Photo de l'étiquette (optionnel)</label>
                    <input type="file" name="photo_etiquette" accept="image/*" class="form-input">
                </div>

                <div class="form-info">
                    <p>Le campus / IUT sera identifié automatiquement via le bon de commande.</p>
                    <p>Si l'identification echoue, le colis sera marque <strong>Non identifié</strong>.</p>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer le colis</button>

            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
