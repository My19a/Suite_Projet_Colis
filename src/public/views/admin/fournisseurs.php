<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fournisseurs – Admin</title>
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
        <a href="/admin/departements">Départements</a>
        <a class="actif" href="/admin/fournisseurs">Fournisseurs</a>
        <a href="/admin/devis">Tous les devis</a>
        <a href="/admin/colis">Tous les colis</a>
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
            <h1 class="page-title">Gestion des fournisseurs</h1>
            <p class="page-subtitle">Consulter, modifier et supprimer les fournisseurs</p>
        </div>
        <a href="/admin/ajouter-fournisseur" class="btn btn-primary">Ajouter un fournisseur</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success" style="margin-bottom:1rem; padding:0.75rem 1rem; background:#d1fae5; border:1px solid #6ee7b7; border-radius:6px; color:#065f46;">
            Fournisseur supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="alert alert-error" style="margin-bottom:1rem; padding:0.75rem 1rem; background:#fee2e2; border:1px solid #fca5a5; border-radius:6px; color:#991b1b;">
            Suppression impossible : ce fournisseur est encore lié à des données (devis, bons de commande…). Supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <div class="section">
        <h3 class="section-title">Liste des fournisseurs</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($fournisseurs)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun fournisseur</td></tr>
                    <?php else: ?>
                        <?php foreach ($fournisseurs as $f): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($f['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($f['contact_nom'] ?: "—") ?></td>
                            <td><?= htmlspecialchars($f['contact_email'] ?: "—") ?></td>
                            <td><?= htmlspecialchars($f['contact_telephone'] ?: "—") ?></td>
                            <td>
                                <a class="btn btn-sm btn-secondary" href="/admin/modifier-fournisseur?id=<?= $f['id_fournisseur'] ?>">Modifier</a>
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
