<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un Colis - Postal UNIV</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style.recherche-colis.css">
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
            <a href="recherche-colis-univ.php" class="actif">🔍 Rechercher</a>
            <a href="ajouter-colis-univ.php">➕ Ajouter un colis</a>
            <a href="historique-transferts.php">📜 Historique transferts</a>
        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/public/views/index.php">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="contenu">
        <h1>🔍 Rechercher un Colis</h1>
        <p class="sous-titre">Recherche par n° suivi, n° commande, département ou ID</p>

        <!-- BARRE DE RECHERCHE -->
        <form method="GET" class="barre-recherche">
            <input type="text" 
                   name="q" 
                   placeholder="Entrez un mot-clé..." 
                   value="<?= htmlspecialchars($motcle) ?>"
                   autofocus>
            <button type="submit">🔍 Rechercher</button>
        </form>

        <?php if (!empty($motcle)): ?>
            <p style="margin: 15px 0; color: #666;">
                <strong>Recherche pour :</strong> "<?= htmlspecialchars($motcle) ?>" 
                — <?= count($resultats) ?> résultat(s)
            </p>
        <?php endif; ?>

        <!-- RESULTATS -->
        <?php if (!empty($resultats)): ?>
            <table class="tableau">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° Suivi</th>
                        <th>N° Commande</th>
                        <th>Date Réception</th>
                        <th>Département</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultats as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c["id_colis"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_suivi"] ?? "—") ?></td>
                            <td><?= htmlspecialchars($c["numero_commande"] ?? "—") ?></td>
                            <td><?= htmlspecialchars($c["date_reception"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?? "Non identifié") ?></td>
                            <td>
                                <?php
                                if ($c["statut_id"] == 1) echo "⏳ En attente";
                                elseif ($c["statut_id"] == 5) echo "🚚 En transfert";
                                elseif ($c["statut_id"] == 6) echo "✅ Transféré";
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
                </tbody>
            </table>
        <?php elseif (!empty($motcle)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px; text-align: center; margin-top: 25px;">
                <strong>❌ Aucun résultat trouvé</strong>
                <p style="margin: 8px 0 0 0;">Essayez avec un autre mot-clé</p>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>