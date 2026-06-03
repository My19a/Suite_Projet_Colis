<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget – Département</title>
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
        <a class="actif" href="/departement/budget">Budget</a>
        <a href="/departement/fournisseurs">Fournisseurs</a>
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
            <h1 class="page-title">Budget du département</h1>
            <p class="page-subtitle">Situation budgétaire actuelle</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <span class="stat-label">Budget total</span>
            <div class="stat-value"><?= number_format($budget["budget_total"], 2, ',', ' ') ?></div>
            <div class="stat-description">EUR alloué</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">Budget utilisé</span>
            <div class="stat-value"><?= number_format($budget["budget_utilise"], 2, ',', ' ') ?></div>
            <div class="stat-description">EUR dépensé</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Budget restant</span>
            <div class="stat-value"><?= number_format($budget["budget_total"] - $budget["budget_utilise"], 2, ',', ' ') ?></div>
            <div class="stat-description">EUR disponible</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Repartition du budget</h2>
        </div>

        <?php
        $total = $budget["budget_total"];
        $utilise = $budget["budget_utilise"];
        $pourcentage = $total > 0 ? round(($utilise / $total) * 100) : 0;
        ?>

        <div style="background: var(--bg); border-radius: var(--radius); padding: 24px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-weight: 600; color: var(--text);">Utilisation</span>
                <span style="font-weight: 600; color: var(--blue);"><?= $pourcentage ?>%</span>
            </div>
            <div style="background: var(--border); border-radius: 10px; height: 12px; overflow: hidden;">
                <div style="background: linear-gradient(90deg, var(--blue) 0%, var(--blue-light) 100%); height: 100%; width: <?= $pourcentage ?>%; border-radius: 10px; transition: width 0.5s ease;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 13px; color: var(--text-muted);">
                <span>0 EUR</span>
                <span><?= number_format($total, 2, ',', ' ') ?> EUR</span>
            </div>
        </div>
    </div>

</main>

</body>
</html>
