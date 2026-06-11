<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche colis – Postal IUT</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal IUT</h2>
        <p>Recherche colis</p>
    </div>

    <nav class="menu">
        <a href="/postal/dashboard">Tableau de bord</a>
        <a href="/postal/confirmation">Confirmation réception</a>
        <a href="/postal/colis/recus">Colis reçus</a>
        <a href="/postal/colis/remis">Colis remis</a>
        <a class="actif" href="/postal/colis/recherche">Recherche colis</a>
        <a href="/postal/colis/non-identifies">Non identifiés</a>
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
            <h1 class="page-title">Recherche de colis</h1>
            <p class="page-subtitle">Trouvez un colis par numéro de suivi, BC, département ou ID</p>
        </div>
    </div>

    <div class="section">
        <form method="get" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <div class="search-container" style="flex: 1; min-width: 300px; margin-bottom: 0;">
                <span class="search-icon-text">&#128269;</span>
                <input type="text" name="q" class="search-input" placeholder="placeholder="placeholder=""N° suivi, BC, departement, ID colis..."" value="<?= htmlspecialchars($_GET["q"] ?? "") ?>">
            </div>
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Resultats</h2>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Bon de commande</th>
                        <th>Département</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resultats)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Aucun résultat</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($resultats as $c): ?>
                        <tr>
                            <td>
                                <a href="/postal/colis/details?id=<?= $c["id_colis"] ?>" class="btn-link">#<?= $c["id_colis"] ?></a>
                            </td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_commande"] ?: "—") ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "—") ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= $c["statut"] ?></span></td>
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
