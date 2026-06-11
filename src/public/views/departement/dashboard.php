<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Département</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2><?= htmlspecialchars($userInfo['departement'] ?? 'Departement') ?></h2>
        <p><?= htmlspecialchars($userInfo['nom'] ?? 'Utilisateur') ?></p>
    </div>

    <nav class="menu">
        <a class="actif" href="/departement/dashboard">Tableau de bord</a>
        <a href="/departement/creer-devis">Creer un devis</a>
        <a href="/departement/mes-devis">Mes devis</a>
        <a href="/departement/bons-commande">Mes bons de commande</a>
        <a href="/departement/mes-colis">Mes colis</a>
        <a href="/departement/budget">Budget</a>
        <a href="/departement/fournisseurs">Fournisseurs</a>
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
            <p class="page-subtitle">Gerez vos devis, commandes et colis</p>
        </div>
        <button class="btn btn-primary" onclick="window.location.href='/departement/creer-devis'">
            Creer un devis
        </button>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <span class="stat-label">Colis total</span>
            <div class="stat-value"><?php echo $stats['colis_total']; ?></div>
            <div class="stat-description">Total des colis</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">Colis en attente</span>
            <div class="stat-value"><?php echo $stats['en_attente']; ?></div>
            <div class="stat-description">A recuperer</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Colis retirés</span>
            <div class="stat-value"><?php echo $stats['retire']; ?></div>
            <div class="stat-description">Receptions confirmees</div>
        </div>
    </div>

    <?php if (isset($budget)): ?>
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Budget du département</h2>
            <span class="section-subtitle">Situation budgétaire</span>
        </div>

        <div class="stats-grid" style="margin-bottom: 0;">
            <div class="stat-card">
                <span class="stat-label">Budget total</span>
                <div class="stat-value" style="font-size: 24px;"><?php echo number_format($budget['budget_total'], 2, ',', ' '); ?> EUR</div>
            </div>
            <div class="stat-card">
                <span class="stat-label">Budget utilisé</span>
                <div class="stat-value" style="font-size: 24px;"><?php echo number_format($budget['budget_utilise'], 2, ',', ' '); ?> EUR</div>
            </div>
            <div class="stat-card">
                <span class="stat-label">Budget restant</span>
                <div class="stat-value" style="font-size: 24px;"><?php echo number_format($budget['budget_restant'], 2, ',', ' '); ?> EUR</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Derniers colis</h2>
            <span class="section-subtitle">Suivez vos livraisons recentes</span>
            <a href="/departement/mes-colis" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Suivi</th>
                        <th>BC lie</th>
                        <th>Destinataire</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">Aucun colis trouve</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $col): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($col['numero_suivi'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($col['numero_commande'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($col['destinataire_nom'] ?? 'Non assigne'); ?></td>
                                <td><?php echo isset($col['date_reception']) ? date('d/m/Y', strtotime($col['date_reception'])) : 'N/A'; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower(str_replace(' ', '_', $col['statut_libelle'])); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $col['statut_libelle'])); ?>
                                    </span>
                                </td>
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
