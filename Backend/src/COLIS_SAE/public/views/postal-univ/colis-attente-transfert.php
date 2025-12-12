<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colis en Attente Transfert - Postal UNIV</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
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
            <a href="colis-attente-transfert.php" class="actif">⏳ En attente transfert</a>
            <a href="colis-transferes.php">✅ Colis transférés</a>
            <a href="recherche-colis-univ.php">🔍 Rechercher</a>
            <a href="ajouter-colis-univ.php">➕ Ajouter un colis</a>
            <a href="historique-transferts.php">📜 Historique transferts</a>
        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/public/views/index.php">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="contenu">
        <h1>⏳ Colis en Attente de Transfert vers IUT</h1>
        <p class="sous-titre">Colis prêts à être transférés au service postal IUT</p>

        <table class="tableau">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>N° Suivi</th>
                    <th>N° Commande</th>
                    <th>Date Réception</th>
                    <th>Département</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($colis)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 25px; color: #666;">
                            ✅ Aucun colis en attente de transfert
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($colis as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c["id_colis"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_suivi"] ?? "—") ?></td>
                            <td><?= htmlspecialchars($c["numero_commande"] ?? "—") ?></td>
                            <td><?= htmlspecialchars($c["date_reception"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?? "Non identifié") ?></td>
                            <td>
                                <a href="colis-details-univ.php?id=<?= $c["id_colis"] ?>" 
                                   style="background: #0d47a1; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-right: 8px;">
                                    Voir
                                </a>
                                <a href="transferer-iut.php?id=<?= $c["id_colis"] ?>" 
                                   style="background: #28a745; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600;"
                                   onclick="return confirm('Transférer ce colis vers l\'IUT ?')">
                                    🚚 Transférer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </main>

</body>
</html>