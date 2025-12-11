<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Colis remis – Service Postal IUT</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css?v=20">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-colis-remis.css?v=1">
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
            <a class="actif" href="/COLIS_SAE/public/postal_iut/colis-remis.php">📤 Colis remis</a>
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

        <h1>📤 Colis remis</h1>
        <p class="sous-titre">Liste de tous les colis livrés aujourd’hui et auparavant</p>

        <table class="tableau">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Département</th>
                    <th>N° commande</th>
                    <th>N° suivi</th>
                    <th>Date réception</th>
                    <th>Date retrait</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($colis as $c): ?>
                <tr>
                    <td>
                        <a href="/COLIS_SAE/public/postal_iut/colis-details.php?id=<?= $c["id_colis"] ?>">
                            #<?= $c["id_colis"] ?>
                        </a>
                    </td>
                    <td><?= $c["departement"] ?: "Non identifié" ?></td>
                    <td><?= $c["numero_commande"] ?></td>
                    <td><?= $c["numero_suivi"] ?></td>
                    <td><?= $c["date_reception"] ?></td>
                    <td><?= $c["date_retrait"] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>

</body>
</html>