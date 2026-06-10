<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réception des colis – Postal Université</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal Université</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a href="/postal-univ/dashboard">Tableau de bord</a>
        <a class="actif" href="/postal-univ/reception">Réception colis</a>
        <a href="/postal-univ/colis">Liste colis</a>
        <a href="/postal-univ/non-identifies">Non identifiés</a>
        <a href="/postal-univ/historique">Historique</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Réception d'un colis</h1>
            <p class="page-subtitle">Enregistrer un colis reçu a l'université avant transfert vers l'IUT</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <form method="post" action="/postal-univ/reception" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="form-label">Numéro de suivi</label>
                    <input type="text" name="numero_suivi" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Numéro de bon de commande</label>
                    <input type="text" name="numero_commande" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Photo de l'étiquette (optionnel)</label>
                    <input type="file" name="photo_etiquette" accept="image/*" class="form-input">
                </div>

                <div class="form-info">
                    <p>Le campus / IUT sera identifié automatiquement via le bon de commande.</p>
                    <p>Si l'identification echoue, le colis sera marque <strong>Non identifié</strong>.</p>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer le colis</button>

            </form>
        </div>
    </div>

</main>

</body>
</html>
