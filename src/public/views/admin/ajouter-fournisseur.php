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

    <div class="section">
        <div class="form-card">
            <form method="post" action="/admin/ajouter-fournisseur">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom du contact</label>
                        <input type="text" name="contact_nom" class="form-input">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="contact_email" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="contact_telephone" class="form-input">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Créer le fournisseur</button>
                    <a href="/admin/fournisseurs" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
