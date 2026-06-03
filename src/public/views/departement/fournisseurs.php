<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fournisseurs – Département</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Département</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a href="/departement/dashboard">Tableau de bord</a>
        <a href="/departement/creer-devis">Creer un devis</a>
        <a href="/departement/mes-devis">Mes devis</a>
        <a href="/departement/bons-commande">Mes bons de commande</a>
        <a href="/departement/mes-colis">Mes colis</a>
        <a href="/departement/budget">Budget</a>
        <a class="actif" href="/departement/fournisseurs">Fournisseurs</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header-simple">
        <a href="/departement/dashboard" class="back-button-simple">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Fournisseurs Autorises</h1>
            <p class="page-subtitle">Liste des fournisseurs validés par l'administration pour passer commande</p>
        </div>
    </div>

    <div class="alert alert-info">
        <span class="alert-icon-text">&#9432;</span>
        <div class="alert-content">
            <strong>Fournisseurs validés uniquement</strong><br>
            Vous ne pouvez passer commande qu'auprès des fournisseurs listés ci-dessous. Ces partenaires ont ete validés par l'administration de l'IUT.
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Liste des Fournisseurs (<?= isset($fournisseurs) ? count($fournisseurs) : 0 ?>)</h2>
            <span class="section-subtitle">Fournisseurs autorisés pour vos commandes</span>
        </div>

        <div class="fournisseurs-grid">
            <?php if (empty($fournisseurs)): ?>
                <div class="empty-state-card-simple">
                    <span class="empty-icon">&#128230;</span>
                    <p>Aucun fournisseur disponible</p>
                </div>
            <?php else: ?>
                <?php foreach ($fournisseurs as $f): ?>
                    <div class="fournisseur-card-simple">
                        <div class="fournisseur-card-header-simple">
                            <div class="fournisseur-icon-simple">
                                <span class="icon-text">&#127970;</span>
                            </div>
                            <div class="fournisseur-info">
                                <h3 class="fournisseur-name"><?= htmlspecialchars($f['nom']) ?></h3>
                                <span class="badge badge-valide">Autorise</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Vue detaillee</h2>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom du fournisseur</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($fournisseurs)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">Aucun fournisseur trouve</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($fournisseurs as $f): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($f['nom']) ?></strong></td>
                                <td><?= $f['contact_nom'] ? htmlspecialchars($f['contact_nom']) : '—' ?></td>
                                <td><?= $f['contact_email'] ? htmlspecialchars($f['contact_email']) : '—' ?></td>
                                <td><?= $f['contact_telephone'] ? htmlspecialchars($f['contact_telephone']) : '—' ?></td>
                                <td><span class="badge badge-valide">Autorise</span></td>
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
