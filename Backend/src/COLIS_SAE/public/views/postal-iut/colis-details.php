<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du colis</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-colis-details.css">
</head>

<body class="tableau-bord">


    <main class="contenu">
        <h1>📦 Détails du colis #<?= $colis["id_colis"] ?></h1>
        <a href="/COLIS_SAE/public/postal_iut/colis-recus.php" class="btn-retour"> ⬅️ Retourner</a>


        <div class="carte details">
            <p><strong>Bon de commande :</strong> <?= $colis["numero_commande"] ?></p>
            <p><strong>Numéro suivi :</strong> <?= $colis["numero_suivi"] ?></p>
            <p><strong>Département :</strong> <?= $colis["departement"] ?></p>
            <p><strong>Date réception :</strong> <?= $colis["date_reception"] ?></p>
            <p><strong>Statut actuel :</strong>
                <?php if ($colis["statut_id"] == 1): ?>
                    En attente
                <?php elseif ($colis["statut_id"] == 2): ?>
                    Livré
                <?php elseif ($colis["statut_id"] == 3): ?>
                    Retiré
                <?php elseif ($colis["statut_id"] == 4): ?>
                    Non identifié
                <?php else: ?>
                    Autre
                <?php endif; ?>
            </p>
        </div>

        <h2>⚙️ Actions</h2>

        <div class="actions">
            <a class="btn-action" href="livrer.php?id=<?= $colis["id_colis"] ?>">✔ Marquer comme livré</a>
            <a class="btn-action" href="retirer.php?id=<?= $colis["id_colis"] ?>">📤 Marquer comme retiré</a>
            <a class="btn-danger" href="non-identifie.php?id=<?= $colis["id_colis"] ?>">🚫 Marquer comme non identifié</a>
            <a class="btn-action" href="modifier-colis.php?id=<?= $colis["id_colis"] ?>">✏️ Modifier le colis</a>

        </div>

        <h2>📜 Historique</h2>

        <table class="tableau">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($historique as $h): ?>
                <tr>
                    <td><?= $h["date_action"] ?></td>
                    <td><?= $h["action"] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>

</body>
</html>
