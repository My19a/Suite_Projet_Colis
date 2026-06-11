<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Départements – Admin</title>
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
        <a href="/presence">Utilisateurs connectés</a>
        <a class="actif" href="/admin/departements">Départements</a>
        <a href="/admin/fournisseurs">Fournisseurs</a>
        <a href="/admin/devis">Tous les devis</a>
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
            <h1 class="page-title">Gestion des départements</h1>
            <p class="page-subtitle">Ajouter, modifier et supprimer les départements</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <h3 class="form-title">Ajouter un département</h3>
            <form method="post" action="/admin/ajouter-departement">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom du département</label>
                        <input type="text" name="nom" class="form-input" placeholder="placeholder="placeholder=""Ex: Informatique"" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Budget total (EUR)</label>
                        <input type="number" name="budget_total" class="form-input" placeholder="placeholder="placeholder=""Ex: 50000"" step="0.01" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Budget total</th>
                        <th>Budget utilisé</th>
                        <th>Budget restant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($departements)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun département</td></tr>
                    <?php else: ?>
                        <?php foreach ($departements as $d): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($d['nom']) ?></strong></td>
                            <td><?= number_format($d['budget_total'], 2, ',', ' ') ?> EUR</td>
                            <td><?= number_format($d['budget_utilise'], 2, ',', ' ') ?> EUR</td>
                            <td><span class="montant"><?= number_format($d['budget_total'] - $d['budget_utilise'], 2, ',', ' ') ?> EUR</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn btn-sm btn-secondary" href="/admin/modifier-departement?id=<?= $d['id_departement'] ?>">Modifier</a>
                                    <a class="btn btn-sm btn-danger" href="/admin/supprimer-departement?id=<?= $d['id_departement'] ?>" onclick="return confirm('Supprimer ce departement ?')">Supprimer</a>
                                </div>
                            </td>
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
