<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colis Reçus - Postal UNIV</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-colis-recus.css">
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
            <a href="colis-recus-univ.php" class="actif">📦 Colis reçus</a>
            <a href="colis-attente-transfert.php">⏳ En attente transfert</a>
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
        <h1>📦 Tous les Colis Reçus (UNIV)</h1>
        <p class="sous-titre">Liste complète des colis enregistrés par le service universitaire</p>

        <!-- TABLEAU -->
        <table class="tableau">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>N° Suivi</th>
                    <th>N° Commande</th>
                    <th>Date Réception</th>
                    <th>Département</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($colis)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 25px; color: #666;">
                            Aucun colis enregistré
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
                                <?php
                                if ($c["statut_id"] == 1) {
                                    echo '<span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 6px; font-weight: 600;">⏳ En attente</span>';
                                } elseif ($c["statut_id"] == 5) {
                                    echo '<span style="background: #ffeaa7; color: #d63031; padding: 4px 10px; border-radius: 6px; font-weight: 600;">🚚 En transfert</span>';
                                } elseif ($c["statut_id"] == 6) {
                                    echo '<span style="background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 6px; font-weight: 600;">✅ Transféré</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="colis-details-univ.php?id=<?= $c["id_colis"] ?>" 
                                   style="background: #0d47a1; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600;">
                                    Voir
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