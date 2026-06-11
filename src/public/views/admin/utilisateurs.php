<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs – Admin</title>
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
        <a class="actif" href="/admin/utilisateurs">Utilisateurs</a>
        <a href="/presence">Utilisateurs connectés</a>
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
            <h1 class="page-title">Gestion des utilisateurs</h1>
            <p class="page-subtitle">Consulter, modifier et supprimer les utilisateurs</p>
        </div>
        <a href="/admin/ajouter-utilisateur" class="btn btn-primary">Ajouter un utilisateur</a>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success" style="margin-bottom:1rem; padding:0.75rem 1rem; background:#d1fae5; border:1px solid #6ee7b7; border-radius:6px; color:#065f46;">
            Utilisateur enregistré avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success" style="margin-bottom:1rem; padding:0.75rem 1rem; background:#d1fae5; border:1px solid #6ee7b7; border-radius:6px; color:#065f46;">
            Utilisateur supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="alert alert-error" style="margin-bottom:1rem; padding:0.75rem 1rem; background:#fee2e2; border:1px solid #fca5a5; border-radius:6px; color:#991b1b;">
            Suppression impossible : cet utilisateur est encore lié à des données (devis, bons de commande, colis…). Réaffectez ou supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>UID CAS</th>
                        <th>Role</th>
                        <th>Département</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($utilisateurs)): ?>
                        <tr><td colspan="6" class="empty-state">Aucun utilisateur</td></tr>
                    <?php else: ?>
                        <?php foreach ($utilisateurs as $u): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($u["fullName"]) ?></strong></td>
                            <td><?= htmlspecialchars($u["email"]) ?></td>
                            <td><?= htmlspecialchars($u["uid_cas"]) ?></td>
                            <td><?= htmlspecialchars($u["role"]) ?></td>
                            <td><?= htmlspecialchars($u["departement"] ?? "—") ?></td>
                            <td>
                                <a href="/admin/modifier-utilisateur?id=<?= $u["id_utilisateur"] ?>" class="btn btn-sm btn-primary">Modifier</a>
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
