<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Colis - Postal UNIV</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-colis-details.css">
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
            <a href="historique-transferts.php">📜 Historique transferts</a>
        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/public/views/index.php">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="contenu">
        <a href="colis-recus-univ.php" class="btn-retour">← Retour à la liste</a>

        <h1>📦 Détails du Colis #<?= htmlspecialchars($colis["id_colis"]) ?></h1>

        <!-- INFORMATIONS DU COLIS -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 3px 12px rgba(0,0,0,0.06); margin-bottom: 25px;">
            <div class="details">
                <p><strong>N° Suivi :</strong> <?= htmlspecialchars($colis["numero_suivi"] ?? "—") ?></p>
                <p><strong>N° Bon de Commande :</strong> <?= htmlspecialchars($colis["numero_commande"] ?? "—") ?></p>
                <p><strong>Date de Réception UNIV :</strong> <?= htmlspecialchars($colis["date_reception"]) ?></p>
                <p><strong>Département :</strong> <?= htmlspecialchars($colis["departement"] ?? "Non identifié") ?></p>
                <p><strong>Commentaire :</strong> <?= htmlspecialchars($colis["commentaire"] ?? "Aucun") ?></p>
                <p><strong>Statut :</strong> 
                    <?php
                    if ($colis["statut_id"] == 1) {
                        echo '<span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 8px; font-weight: 600;">⏳ En attente de transfert</span>';
                    } elseif ($colis["statut_id"] == 5) {
                        echo '<span style="background: #ffeaa7; color: #d63031; padding: 6px 14px; border-radius: 8px; font-weight: 600;">🚚 En cours de transfert</span>';
                    } elseif ($colis["statut_id"] == 6) {
                        echo '<span style="background: #d4edda; color: #155724; padding: 6px 14px; border-radius: 8px; font-weight: 600;">✅ Transféré à l\'IUT</span>';
                    }
                    ?>
                </p>
                <?php if (!empty($colis["date_transfert_iut"])): ?>
                    <p><strong>Date de Transfert IUT :</strong> <?= htmlspecialchars($colis["date_transfert_iut"]) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="actions">
            <?php if ($colis["statut_id"] == 1): ?>
                <a href="transferer-iut.php?id=<?= $colis["id_colis"] ?>" 
                   class="btn-action" 
                   onclick="return confirm('Confirmer le transfert vers l\'IUT ?')">
                    🚚 Transférer vers IUT
                </a>
            <?php elseif ($colis["statut_id"] == 5): ?>
                <a href="confirmer-transfert.php?id=<?= $colis["id_colis"] ?>" 
                   class="btn-action" 
                   onclick="return confirm('Confirmer la réception par l\'IUT ?')">
                    ✅ Confirmer réception IUT
                </a>
            <?php else: ?>
                <span style="color: #28a745; font-weight: 600;">✅ Colis transféré avec succès</span>
            <?php endif; ?>
        </div>

        <!-- HISTORIQUE -->
        <h2 class="titre-section">📜 Historique des Actions</h2>
        <table class="tableau">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($historique)): ?>
                    <tr>
                        <td colspan="2" style="text-align:center; padding: 20px;">Aucun historique</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($historique as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h["date_action"]) ?></td>
                            <td><?= htmlspecialchars($h["action"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </main>

</body>
</html>