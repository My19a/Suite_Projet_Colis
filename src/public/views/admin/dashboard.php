<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Administrateur</title>
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
        <a class="actif" href="/admin/dashboard">Tableau de bord</a>
        <a href="/admin/utilisateurs">Utilisateurs</a>
        <a href="/admin/departements">Départements</a>
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
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue globale du système</p>
        </div>
        <form method="post" action="/admin/test-mail" style="display:flex; gap:0.5rem; align-items:center;">
            <input type="email" name="to" class="form-input" placeholder="destinataire@exemple.com"
                   value="<?= htmlspecialchars(getenv('MAIL_TEST_TO') ?: '') ?>" required
                   style="min-width:240px;">
            <button type="submit" class="btn btn-secondary">Envoyer mail de test</button>
        </form>
    </div>

    <?php if (isset($_GET['mail'])): ?>
        <?php if ($_GET['mail'] === 'ok'): ?>
            <div class="alert alert-success" style="margin-bottom:1rem; padding:0.75rem 1rem; background:#d1fae5; border:1px solid #6ee7b7; border-radius:6px; color:#065f46;">
                Mail de test envoyé avec succès vers <strong><?= htmlspecialchars($_GET['to'] ?? '') ?></strong>.
            </div>
        <?php else: ?>
            <div class="alert alert-error" style="margin-bottom:1rem; padding:0.75rem 1rem; background:#fee2e2; border:1px solid #fca5a5; border-radius:6px; color:#991b1b;">
                Erreur lors de l'envoi : <?= htmlspecialchars($_GET['msg'] ?? 'inconnue') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Utilisateurs</span>
            <div class="stat-value"><?= $stats["utilisateurs"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-blue">
            <span class="stat-label">Devis</span>
            <div class="stat-value"><?= $stats["devis"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">Bons de commande</span>
            <div class="stat-value"><?= $stats["bons"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Colis</span>
            <div class="stat-value"><?= $stats["colis"] ?></div>
            <div class="stat-description">Total</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Repartition des utilisateurs par role</h2>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($roles)): ?>
                        <tr><td colspan="2" class="empty-state">Aucun role trouve</td></tr>
                    <?php else: ?>
                        <?php foreach ($roles as $r): ?>
                        <tr>
                            <td><strong><?= ucfirst(htmlspecialchars($r["libelle"])) ?></strong></td>
                            <td><?= $r["total"] ?></td>
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
