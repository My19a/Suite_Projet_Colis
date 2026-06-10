<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes colis – Département</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Département</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a href="/departement/dashboard">Tableau de bord</a>
        <a href="/departement/creer-devis">Creer un devis</a>
        <a href="/departement/mes-devis">Mes devis</a>
        <a href="/departement/bons-commande">Mes bons de commande</a>
        <a class="actif" href="/departement/mes-colis">Mes colis</a>
        <a href="/departement/budget">Budget</a>
        <a href="/departement/fournisseurs">Fournisseurs</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header-simple">
        <a href="/departement/dashboard" class="back-button-simple">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Mes Colis</h1>
            <p class="page-subtitle">Suivi de vos livraisons</p>
        </div>
    </div>

    <div class="search-container">
        <span class="search-icon-text">&#128269;</span>
        <input type="text" class="search-input" placeholder="placeholder="placeholder=""Rechercher par numéro de suivi, BC ou statut..."" id="rechercheColis" onkeyup="filtrerColis()">
    </div>

    <?php
    $totalColis = count($colis);
    $enTransit = 0;
    $enAttente = 0;
    $livres = 0;

    foreach ($colis as $c) {
        if ($c['statut'] === 'Transfere vers IUT') $enTransit++;
        if ($c['statut'] === 'En attente') $enAttente++;
        if ($c['statut'] === 'Livre') $livres++;
    }
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total colis</span>
            <div class="stat-value"><?= $totalColis ?></div>
        </div>
        <div class="stat-card stat-blue">
            <span class="stat-label">En transit</span>
            <div class="stat-value"><?= $enTransit ?></div>
        </div>
        <div class="stat-card stat-warning">
            <span class="stat-label">En attente</span>
            <div class="stat-value"><?= $enAttente ?></div>
        </div>
        <div class="stat-card stat-success">
            <span class="stat-label">Livrés</span>
            <div class="stat-value"><?= $livres ?></div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Liste des colis</h2>
            <span class="section-subtitle"><?= $totalColis ?> colis trouve(s)</span>
        </div>

        <div class="table-container">
            <table class="data-table" id="tableauColis">
                <thead>
                    <tr>
                        <th>N° Suivi</th>
                        <th>Bon de commande</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">Aucun colis trouve</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                            <tr class="ligne-colis">
                                <td><strong><?= htmlspecialchars($c['numero_suivi'] ?? '—') ?></strong></td>
                                <td><?= htmlspecialchars($c['numero_commande']) ?></td>
                                <td><?= $c['date_reception'] ? date('d/m/Y', strtotime($c['date_reception'])) : '—' ?></td>
                                <td>
                                    <?php $statutAffichage = $c['statut'] === 'Retire' ? 'Livre' : $c['statut']; ?>
                                    <span class="badge badge-<?= strtolower(str_replace(' ', '_', $statutAffichage)) ?>"><?= $statutAffichage ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<script>
function filtrerColis() {
    const input = document.getElementById('rechercheColis');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('tableauColis');
    const rows = table.getElementsByClassName('ligne-colis');

    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    }
}
</script>

</body>
</html>
