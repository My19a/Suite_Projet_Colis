<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier fournisseur – Admin</title>
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
            <h1 class="page-title">Modifier le fournisseur</h1>
            <p class="page-subtitle">Mettre a jour les informations du fournisseur</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <form method="post" action="/admin/update-fournisseur">
                <input type="hidden" name="id_fournisseur" value="<?= $fournisseur['id_fournisseur'] ?>">

                <div class="form-group">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-input" value="<?= htmlspecialchars($fournisseur['nom']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nom du contact</label>
                    <input type="text" name="contact_nom" class="form-input" value="<?= htmlspecialchars($fournisseur['contact_nom'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="contact_email" class="form-input" value="<?= htmlspecialchars($fournisseur['contact_email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="contact_telephone" class="form-input" value="<?= htmlspecialchars($fournisseur['contact_telephone'] ?? '') ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a class="btn btn-secondary" href="/admin/fournisseurs">Annuler</a>
                </div>
            </form>
        </div>
    </div>

</main>

</body>
</html>
