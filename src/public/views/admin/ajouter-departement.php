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

    <div class="section">
        <div class="form-card">
            <form method="post" action="/admin/ajouter-departement">

                <div class="form-group">
                    <label class="form-label">Nom du département</label>
                    <input type="text" name="nom" class="form-input" placeholder="Ex: Informatique" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Budget total (EUR)</label>
                    <input type="number" name="budget_total" class="form-input" placeholder="Ex: 50000" step="0.01" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Créer le département</button>
                    <a href="/admin/departements" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
