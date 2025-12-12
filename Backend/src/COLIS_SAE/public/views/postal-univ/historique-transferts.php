<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique Transferts - Postal UNIV</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-historique.css">
</head>

<body class="tableau-bord">

    <!-- BARRE LATERALE -->
    <aside class="barre-laterale">
        <div class="entete-barre">
            <img src="/COLIS_SAE/assets/img/logo-iutv.png" alt="Logo" class="logo">
            <h2>Postal UNIV</h2>
            <p>Service Universitaire</p>
        </div>

        <nav class="menu">
            <a href="dashboard.php">📊 Tableau de bord</a>
            <a href="colis-recus-univ.php">📦 Colis reçus</a>
            <a href="colis-attente-transfert.php">⏳ En attente transfert</a>
            <a href="colis-transferes.php">✅ Colis transférés</a>
            <a href="recherche-colis-univ.php">🔍 Rechercher</a>
            <a href="ajouter-colis-univ.php">➕ Ajouter un colis</a>
            <a href="historique-transferts.php" class="actif">📜 Historique transferts</a>
        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/public/views/index.php">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="contenu">
        <h1>📜 Historique des Transferts UNIV → IUT</h1>
        <p class="sous-titre">Traçabilité complète des transferts entre les services postaux</p>

        <table class="tableau">
            <thead>
                <tr>
                    <th>Date Action</th>
                    <th>Colis ID</th>
                    <th>N° Suivi</th>
                    <th>N° Commande</th>
                    <th>Département</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($historique)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 25px; color: #666;">
                            Aucun historique de transfert
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($historique as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h["date_action"]) ?></td>
                            <td>
                                <a href="colis-details-univ.php?id=<?= $h["colis_id"] ?>">
                                    #<?= htmlspecialchars($h["colis_id"]) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($h["numero_suivi"] ?? "—") ?></td>
                            <td><?= htmlspecialchars($h["numero_commande"] ?? "—") ?></td>
                            <td><?= htmlspecialchars($h["departement"] ?? "Non identifié") ?></td>
                            <td><?= htmlspecialchars($h["action"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </main>

</body>
</html>