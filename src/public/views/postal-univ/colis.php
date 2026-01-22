<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des colis – Postal Universite</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal Universite</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a href="/postal-univ/dashboard">Tableau de bord</a>
        <a href="/postal-univ/reception">Reception colis</a>
        <a class="actif" href="/postal-univ/colis">Liste colis</a>
        <a href="/postal-univ/non-identifies">Non identifies</a>
        <a href="/postal-univ/historique">Historique</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Liste des colis recus</h1>
            <p class="page-subtitle">Tous les colis receptionnes par l'universite</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>N° bon de commande</th>
                        <th>Campus / IUT</th>
                        <th>Statut</th>
                        <th>Date reception</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr><td colspan="7" class="empty-state">Aucun colis</td></tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td>#<?= $c["id_colis"] ?></td>
                            <td><strong><?= htmlspecialchars($c["numero_suivi"] ?: "—") ?></strong></td>
                            <td><?= htmlspecialchars($c["numero_commande"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "Non identifie") ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= $c["statut"] ?></span></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td>
                                <?php if ($c["statut_id"] == 5): ?>
                                    <a class="btn btn-sm btn-primary" href="/postal-univ/transferer?id=<?= $c["id_colis"] ?>">Transferer vers IUT</a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
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
