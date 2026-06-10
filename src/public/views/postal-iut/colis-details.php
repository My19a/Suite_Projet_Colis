<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details du colis – Postal IUT</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal IUT</h2>
        <p>Details colis</p>
    </div>

    <nav class="menu">
        <a href="/postal/dashboard">Tableau de bord</a>
        <a href="/postal/confirmation">Confirmation réception</a>
        <a href="/postal/colis/recus">Colis reçus</a>
        <a href="/postal/colis/remis">Colis remis</a>
        <a href="/postal/colis/recherche">Recherche colis</a>
        <a href="/postal/colis/non-identifies">Non identifiés</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header-simple">
        <a href="/postal/colis/recus" class="back-button-simple">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Details du colis #<?= $colis["id_colis"] ?></h1>
        </div>
    </div>

    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="stat-card">
            <span class="stat-label">N° suivi</span>
            <div class="stat-value" style="font-size: 18px;"><?= htmlspecialchars($colis["numero_suivi"] ?: "—") ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Bon de commande</span>
            <div class="stat-value" style="font-size: 18px;"><?= htmlspecialchars($colis["numero_commande"] ?: "—") ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Département</span>
            <div class="stat-value" style="font-size: 18px;"><?= htmlspecialchars($colis["departement"] ?: "Non identifie") ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Statut</span>
            <div style="margin-top: 8px;">
                <span class="badge badge-<?= strtolower(str_replace(' ', '_', $colis["statut"])) ?>"><?= $colis["statut"] ?></span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Informations</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Date réception</p>
                <p style="font-weight: 600;"><?= $colis["date_reception"] ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Date retrait</p>
                <p style="font-weight: 600;"><?= $colis["date_retrait"] ?: "—" ?></p>
            </div>
            <div style="grid-column: 1 / -1;">
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Commentaire</p>
                <p style="font-weight: 500;"><?= htmlspecialchars($colis["commentaire"] ?: "Aucun commentaire") ?></p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Historique</h2>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)): ?>
                        <tr>
                            <td colspan="2" class="empty-state">Aucun historique disponible</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($historique as $h): ?>
                        <tr>
                            <td><?= $h["date_action"] ?></td>
                            <td><span class="badge"><?= htmlspecialchars($h["action"]) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>
