<?php
$titre = 'Modifier le colis #<?= htmlspecialchars($colis[\'id_colis\']) ?> – Postal IUT';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header-simple">
        <a href="/postal/colis/details?id=<?= $colis['id_colis'] ?>" class="lien-retour">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Modifier le colis #<?= htmlspecialchars($colis['id_colis']) ?></h1>
        </div>
    </div>

    <div class="bloc" style="max-width: 600px;">
        <form method="post" action="/postal/colis/update">
            <input type="hidden" name="id_colis" value="<?= htmlspecialchars($colis['id_colis']) ?>">

            <div class="champ">
                <label class="etiquette">Numéro suivi</label>
                <input type="text" name="numero_suivi" class="saisie" value="<?= htmlspecialchars($colis['numero_suivi'] ?? '') ?>">
            </div>

            <div class="champ">
                <label class="etiquette">Bon de commande</label>
                <select name="bon_commande_id" class="liste-deroulante">
                    <option value="">— Aucun —</option>
                    <?php foreach ($bonCommandes as $b): ?>
                        <option value="<?= $b['id_bon_commande'] ?>" <?= (isset($colis['bon_commande_id']) && $colis['bon_commande_id'] == $b['id_bon_commande']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b['numero_commande']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ">
                <label class="etiquette">Département</label>
                <select name="destinataire_id" class="liste-deroulante">
                    <option value="">— Aucun —</option>
                    <?php foreach ($departements as $d): ?>
                        <option value="<?= $d['id_departement'] ?>" <?= (isset($colis['destinataire_id']) && $colis['destinataire_id'] == $d['id_departement']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ">
                <label class="etiquette">Statut</label>
                <select name="statut_id" class="liste-deroulante">
                    <?php foreach ($statuts as $s): ?>
                        <option value="<?= $s['id_statut'] ?>" <?= (isset($colis['statut_id']) && $colis['statut_id'] == $s['id_statut']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ">
                <label class="etiquette">Commentaire</label>
                <textarea name="commentaire" class="saisie" rows="4"><?= htmlspecialchars($colis['commentaire'] ?? '') ?></textarea>
            </div>

            <div class="formulaire-boutons">
                <a href="/postal/colis/details?id=<?= $colis['id_colis'] ?>" class="bouton bouton-secondaire">Annuler</a>
                <button type="submit" class="bouton bouton-principal">Enregistrer</button>
            </div>
        </form>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
