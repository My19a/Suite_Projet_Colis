<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord – Directeur</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Directeur IUT</h2>
        <p>Validation et signature</p>
    </div>

    <nav class="menu">
        <a class="actif" href="/directeur/dashboard">Tableau de bord</a>
        <a href="/directeur/devis">Devis à signer</a>
        <a href="/directeur/bons-commande">Bons de commande</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="utilisateur-connecte">
        <div class="utilisateur-nom"><?= isset($_SESSION["user"]) ? htmlspecialchars($_SESSION["user"]->getFullName()) : "" ?></div>
        <div class="utilisateur-role"><?= isset($_SESSION["user"]) ? htmlspecialchars($_SESSION["user"]->getRole()) : "" ?></div>
    </div>
    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Validation et suivi des décisions financières</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-warning">
            <span class="stat-label">Devis à signer</span>
            <div class="stat-value"><?= $stats["devis_attente"] ?></div>
            <div class="stat-description">En attente</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">BC signés</span>
            <div class="stat-value"><?= $stats["bc_signes"] ?></div>
            <div class="stat-description">Bons de commande</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Devis validés par le service financier</h2>
            <a href="/directeur/devis" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Montant</th>
                        <th>Date demande</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun devis à signer</td></tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                        <tr>
                            <td>#<?= $d["id_devis"] ?></td>
                            <td><strong><?= htmlspecialchars($d["objet"]) ?></strong></td>
                            <td><span class="montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></td>
                            <td><?= $d["date_demande"] ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/directeur/signer-devis?id=<?= $d["id_devis"] ?>">Signer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Historique des bons de commande</h2>
            <a href="/directeur/bons-commande" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° commande</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bons)): ?>
                        <tr><td colspan="3" class="empty-state">Aucun bon de commande</td></tr>
                    <?php else: ?>
                        <?php foreach ($bons as $b): ?>
                        <tr>
                            <td>#<?= $b["id_bon_commande"] ?></td>
                            <td><strong><?= htmlspecialchars($b["numero_commande"]) ?></strong></td>
                            <td><?= $b["date_commande"] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<?php require __DIR__ . "/../partials/tutoriel.php"; ?>
</body>
</html>
