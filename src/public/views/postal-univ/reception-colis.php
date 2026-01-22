<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception des colis – Postal Universite</title>
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
        <a class="actif" href="/postal-univ/reception">Reception colis</a>
        <a href="/postal-univ/colis">Liste colis</a>
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
            <h1 class="page-title">Reception d'un colis</h1>
            <p class="page-subtitle">Enregistrer un colis recu a l'universite avant transfert vers l'IUT</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <form method="post" action="/postal-univ/reception" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="form-label">Numero de suivi</label>
                    <input type="text" name="numero_suivi" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Numero de bon de commande</label>
                    <input type="text" name="numero_commande" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Photo de l'etiquette (optionnel)</label>
                    <input type="file" name="photo_etiquette" accept="image/*" class="form-input">
                </div>

                <div class="form-info">
                    <p>Le campus / IUT sera identifie automatiquement via le bon de commande.</p>
                    <p>Si l'identification echoue, le colis sera marque <strong>Non identifie</strong>.</p>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer le colis</button>

            </form>
        </div>
    </div>

</main>

</body>
</html>
