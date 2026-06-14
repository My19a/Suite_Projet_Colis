<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colis recus – Postal IUT</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal IUT</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a href="/postal/dashboard">Tableau de bord</a>
        <a href="/postal/confirmation">Confirmation reception</a>
        <a class="actif" href="/postal/colis/recus">Colis recus</a>
        <a href="/postal/colis/remis">Colis remis</a>
        <a href="/postal/colis/recherche">Recherche colis</a>
        <a href="/postal/colis/non-identifies">Non identifies</a>
        <a href="/postal/colis/ajouter">Ajouter un colis</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis recus a l'IUT</h1>
            <p class="page-subtitle">Colis transferes depuis l'universite</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Departement</th>
                        <th>Date reception</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Aucun colis recu</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td><a href="/postal/colis/details?id=<?= $c["id_colis"] ?>" class="btn-link">#<?= $c["id_colis"] ?></a></td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "Non identifie") ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= ucfirst(str_replace('_', ' ', $c["statut"])) ?></span></td>                            <td>
                                <a class="btn btn-sm btn-success" href="/postal/colis/retirer?id=<?= $c["id_colis"] ?>">Retire</a>
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
