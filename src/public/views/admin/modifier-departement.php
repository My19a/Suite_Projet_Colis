<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier departement – Admin</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Administrateur</h2>
        <p>Gestion du systeme</p>
    </div>

    <nav class="menu">
        <a href="/admin/dashboard">Tableau de bord</a>
        <a href="/admin/utilisateurs">Utilisateurs</a>
        <a class="actif" href="/admin/departements">Departements</a>
        <a href="/admin/fournisseurs">Fournisseurs</a>
        <a href="/admin/devis">Tous les devis</a>
        <a href="/admin/colis">Tous les colis</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Modifier le departement</h1>
            <p class="page-subtitle">Mettre a jour les informations du departement</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <form method="post" action="/admin/update-departement">
                <input type="hidden" name="id_departement" value="<?= $departement['id_departement'] ?>">

                <div class="form-group">
                    <label class="form-label">Nom du departement</label>
                    <input type="text" name="nom" class="form-input" value="<?= htmlspecialchars($departement['nom']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Budget total (EUR)</label>
                    <input type="number" name="budget_total" class="form-input" value="<?= $departement['budget_total'] ?>" step="0.01" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a class="btn btn-secondary" href="/admin/departements">Annuler</a>
                </div>
            </form>
        </div>
    </div>

</main>

</body>
</html>
