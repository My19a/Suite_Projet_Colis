<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un colis – Service Postal IUT</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-ajouter-colis.css?v=1">
</head>

<body class="tableau-bord">

    <!-- BARRE LATERALE -->
    <aside class="barre-laterale">
        <div class="entete-barre">
            <img src="/COLIS_SAE/assets/img/logo-iutv.png" class="logo">
            <h2>IUT Colis</h2>
            <p>Service Postal</p>
        </div>

        <nav class="menu">
            <a href="/COLIS_SAE/public/postal_iut/postal-iut.php">📦 Tableau de bord</a>
            <a href="/COLIS_SAE/public/postal_iut/colis-recus.php">📥 Colis reçus</a>
            <a href="/COLIS_SAE/public/postal_iut/colis-remis.php">📤 Colis remis</a>
            <a href="/COLIS_SAE/public/postal_iut/recherche-colis.php">🔍 Recherche colis</a>
            <a href="/COLIS_SAE/public/postal_iut/non-identifies.php">❓ Colis non identifiés</a>
            <a href="/COLIS_SAE/public/postal_iut/historique.php">📜 Historique global</a>

        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/logout.php">🚪 Déconnexion</a>
        </div>
    </aside>


    <!-- CONTENU PRINCIPAL -->
    <main class="contenu">

        <h1>📦 Ajouter un colis</h1>
        <p class="sous-titre">Enregistrer l’arrivée d’un nouveau colis dans le système</p>

        <?php if (!empty($message)): ?>
            <div class="message">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-colis">

            <label>Numéro du bon de commande (BC) :</label>
            <input type="text" name="numero_bc" required>

            <label>Numéro de suivi :</label>
            <input type="text" name="numero_suivi">

            <label>Commentaire :</label>
            <textarea name="commentaire" rows="4"></textarea>

            <button type="submit" class="btn-valider">➕ Ajouter le colis</button>

        </form>

    </main>

</body>
</html>
