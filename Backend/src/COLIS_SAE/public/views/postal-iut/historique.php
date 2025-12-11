<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique global – Service Postal IUT</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css?v=11">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-historique.css?v=1">
</head>

<body class="tableau-bord">

    <!-- Barres latérales -->
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
            <a class="actif" href="/COLIS_SAE/public/postal_iut/historique.php">📜 Historique global</a>
        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/logout.php">🚪 Déconnexion</a>
        </div>
    </aside>

    <main class="contenu">

        <h1>📜 Historique global</h1>
        <p class="sous-titre">Dernières actions effectuées sur tous les colis</p>

        <table class="tableau">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Colis</th>
                    <th>N° commande</th>
                    <th>N° suivi</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($historique as $h): ?>
                <tr>
                    <td><?= $h["date_action"] ?></td>

                    <td>
                        <a href="/COLIS_SAE/public/postal_iut/colis-details.php?id=<?= $h["colis_id"] ?>">
                            #<?= $h["colis_id"] ?>
                        </a>
                    </td>

                    <td><?= $h["numero_commande"] ?: "—" ?></td>
                    <td><?= $h["numero_suivi"] ?: "—" ?></td>

                    <td><?= $h["action"] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>

</body>
</html>
