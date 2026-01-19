<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le colis #<?= htmlspecialchars($colis['id_colis']) ?></title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css?v=1">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-modifier-colis.css?v=1">
</head>
<body class="tableau-bord">

<main class="contenu">
    <h1>✏ Modifier le colis #<?= htmlspecialchars($colis['id_colis']) ?></h1>

    <form method="post" action="/COLIS_SAE/public/postal_iut/update-colis.php" class="form-modifier">
        <input type="hidden" name="id_colis" value="<?= htmlspecialchars($colis['id_colis']) ?>">

        <label>Numéro suivi :</label>
        <input type="text" name="numero_suivi" value="<?= htmlspecialchars($colis['numero_suivi'] ?? '') ?>">

        <label>Bon de commande :</label>
        <select name="bon_commande_id">
            <option value="">— Aucun —</option>
            <?php foreach ($bonCommandes as $b): ?>
                <option value="<?= $b['id_bon_commande'] ?>"
                    <?= (isset($colis['bon_commande_id']) && $colis['bon_commande_id'] == $b['id_bon_commande']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['numero_commande']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Département :</label>
        <select name="destinataire_id">
            <option value="">— Aucun —</option>
            <?php foreach ($departements as $d): ?>
                <option value="<?= $d['id_departement'] ?>"
                    <?= (isset($colis['destinataire_id']) && $colis['destinataire_id'] == $d['id_departement']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Statut :</label>
        <select name="statut_id">
            <?php foreach ($statuts as $s): ?>
                <option value="<?= $s['id_statut'] ?>"
                    <?= (isset($colis['statut_id']) && $colis['statut_id'] == $s['id_statut']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Commentaire :</label>
        <textarea name="commentaire"><?= htmlspecialchars($colis['commentaire'] ?? '') ?></textarea>

        <button type="submit" class="btn-action">💾 Enregistrer</button>
    </form>

</main>
</body>
</html>
