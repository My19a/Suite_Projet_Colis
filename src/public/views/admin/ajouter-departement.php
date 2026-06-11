<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un département – Admin</title>
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
        <a class="actif" href="/admin/departements">Départements</a>
        <a href="/admin/fournisseurs">Fournisseurs</a>
        <a href="/admin/devis">Tous les devis</a>
        <a href="/admin/colis">Tous les colis</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Ajouter un département</h1>
            <p class="page-subtitle">Créer un nouveau département</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <form method="post" action="/admin/ajouter-departement">

                <div class="form-group">
                    <label class="form-label">Nom du département</label>
                    <input type="text" name="nom" class="form-input" placeholder="Ex: Informatique" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Budget total (EUR)</label>
                    <input type="number" name="budget_total" class="form-input" placeholder="Ex: 50000" step="0.01" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Créer le département</button>
                    <a href="/admin/departements" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

</main>

</body>
</html>
