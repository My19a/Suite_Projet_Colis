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
        <a href="/presence">Utilisateurs connectés</a>
        <a href="/admin/departements">Départements</a>
        <a class="actif" href="/admin/fournisseurs">Fournisseurs</a>
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
            <h1 class="page-title">Gestion des fournisseurs</h1>
            <p class="page-subtitle">Ajout, modification et suppression des fournisseurs</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <h3 class="form-title">Ajouter un fournisseur</h3>
            <form method="post" action="/admin/ajouter-fournisseur">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom du contact</label>
                        <input type="text" name="contact_nom" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="contact_email" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="contact_telephone" class="form-input">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

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
