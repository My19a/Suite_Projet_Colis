<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Postal UNIV</title>
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
            <a href="dashboard.php" class="actif">📊 Tableau de bord</a>
            <a href="colis-recus-univ.php">📦 Colis reçus</a>
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
        <h1>📊 Tableau de Bord - Service Postal UNIV</h1>
        <p class="sous-titre">Vue d'ensemble des colis universitaires</p>

        <!-- ACTIONS RAPIDES -->
        <div class="actions-rapides">
            <a href="ajouter-colis-univ.php" class="btn-action">➕ Enregistrer un colis</a>
            <a href="colis-attente-transfert.php" class="btn-action">🚚 Transférer vers IUT</a>
            <a href="recherche-colis-univ.php" class="btn-action">🔍 Rechercher</a>
        </div>

        <!-- CARTES STATISTIQUES -->
        <div class="cartes">
            <div class="carte">
                <h3>📥 Reçus aujourd'hui</h3>
                <p class="valeur"><?= $stats["recus_aujourdhui"] ?></p>
            </div>

            <div class="carte">
                <h3>⏳ En attente transfert</h3>
                <p class="valeur"><?= $stats["en_attente_transfert"] ?></p>
            </div>

            <div class="carte">
                <h3>✅ Transférés aujourd'hui</h3>
                <p class="valeur"><?= $stats["transferes_auj"] ?></p>
            </div>

            <div class="carte">
                <h3>📦 Total colis UNIV</h3>
                <p class="valeur"><?= $stats["total_colis"] ?></p>
            </div>
        </div>

        <!-- COLIS RECENTS -->
        <h2 class="titre-section">📦 Colis récents</h2>
        <table class="tableau">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>N° Suivi</th>
                    <th>Date réception</th>
                    <th>Département</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($colis_recents)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 20px;">Aucun colis récent</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($colis_recents as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c["id_colis"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= htmlspecialchars($c["date_reception"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?? "Non identifié") ?></td>
                            <td>
                                <?php
                                if ($c["statut_id"] == 1) echo "🟡 En attente";
                                elseif ($c["statut_id"] == 5) echo "🟠 En transfert";
                                elseif ($c["statut_id"] == 6) echo "🟢 Transféré IUT";
                                ?>
                            </td>
                            <td>
                                <a href="colis-details-univ.php?id=<?= $c["id_colis"] ?>" 
                                   style="color: #0d47a1; font-weight: bold; text-decoration: none;">
                                    Voir détails
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- REPARTITION PAR DEPARTEMENT -->
        <h2 class="titre-section">📍 Colis par département</h2>
        <table class="tableau">
            <thead>
                <tr>
                    <th>Département</th>
                    <th>Nombre de colis</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($colis_departements)): ?>
                    <tr>
                        <td colspan="2" style="text-align:center; padding: 20px;">Aucune donnée</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($colis_departements as $dep): ?>
                        <tr>
                            <td><?= htmlspecialchars($dep["departement"] ?? "Non identifié") ?></td>
                            <td><?= htmlspecialchars($dep["total"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </main>

</body>
</html>