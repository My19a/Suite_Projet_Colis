<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les devis – Admin</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Administrateur</h2>
        <p>Gestion du système</p>
    </div>

    <nav class="menu">
        <a href="/admin/dashboard">Tableau de bord</a>
        <a href="/admin/utilisateurs">Utilisateurs</a>
        <a href="/presence">Qui est connecte</a>
        <a href="/admin/departements">Départements</a>
        <a href="/admin/fournisseurs">Fournisseurs</a>
        <a class="actif" href="/admin/devis">Tous les devis</a>
        <a href="/admin/colis">Tous les colis</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tous les devis</h1>
            <p class="page-subtitle">Vue globale de l'ensemble des devis du système</p>
        </div>
    </div>

    <?php if (!empty($stats)): ?>
    <div class="stats-grid">
        <?php foreach ($stats as $s): ?>
        <div class="stat-card">
            <div class="stat-value"><?= $s['total'] ?></div>
            <div class="stat-label"><?= ucfirst(str_replace('_', ' ', $s['statut'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="search-card">
            <form method="get" class="search-form">
                <input type="text" name="q" class="form-input" placeholder="placeholder="placeholder=""Rechercher un devis (objet, departement, fournisseur, statut)"" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Département</th>
                        <th>Fournisseur</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr><td colspan="7" class="empty-state">Aucun devis trouve</td></tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                        <tr>
                            <td>#<?= $d['id_devis'] ?></td>
                            <td><strong><?= htmlspecialchars($d['objet']) ?></strong></td>
                            <td><?= htmlspecialchars($d['departement'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($d['fournisseur'] ?? '—') ?></td>
                            <td><span class="montant"><?= number_format($d['montant_estime'], 2, ',', ' ') ?> EUR</span></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $d['statut'])) ?>"><?= ucfirst(str_replace('_', ' ', $d['statut'])) ?></span></td>
                            <td><?= $d['date_demande'] ?></td>
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
