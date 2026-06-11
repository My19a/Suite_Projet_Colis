<?php
$titre = 'Modifier le colis #<?= htmlspecialchars($colis[\'id_colis\']) ?> – Postal IUT';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header-simple">
        <a href="/postal/colis/details?id=<?= $colis['id_colis'] ?>" class="back-button-simple">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Modifier le colis #<?= htmlspecialchars($colis['id_colis']) ?></h1>
        </div>
    </div>

    <div class="section" style="max-width: 600px;">
        <form method="post" action="/postal/colis/update">
            <input type="hidden" name="id_colis" value="<?= htmlspecialchars($colis['id_colis']) ?>">

            <div class="form-group">
                <label class="form-label">Numéro suivi</label>
                <input type="text" name="numero_suivi" class="form-input" value="<?= htmlspecialchars($colis['numero_suivi'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Bon de commande</label>
                <select name="bon_commande_id" class="form-select">
                    <option value="">— Aucun —</option>
                    <?php foreach ($bonCommandes as $b): ?>
                        <option value="<?= $b['id_bon_commande'] ?>" <?= (isset($colis['bon_commande_id']) && $colis['bon_commande_id'] == $b['id_bon_commande']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b['numero_commande']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Département</label>
                <select name="destinataire_id" class="form-select">
                    <option value="">— Aucun —</option>
                    <?php foreach ($departements as $d): ?>
                        <option value="<?= $d['id_departement'] ?>" <?= (isset($colis['destinataire_id']) && $colis['destinataire_id'] == $d['id_departement']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Statut</label>
                <select name="statut_id" class="form-select">
                    <?php foreach ($statuts as $s): ?>
                        <option value="<?= $s['id_statut'] ?>" <?= (isset($colis['statut_id']) && $colis['statut_id'] == $s['id_statut']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Commentaire</label>
                <textarea name="commentaire" class="form-input" rows="4"><?= htmlspecialchars($colis['commentaire'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <a href="/postal/colis/details?id=<?= $colis['id_colis'] ?>" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
