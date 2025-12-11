<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Colis reçus – Service Postal IUT</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css?v=20">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-colis-recus.css?v=1">
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
            <a class="actif" href="/COLIS_SAE/public/postal_iut/colis-recus.php">📥 Colis reçus</a>
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

        <h1>📥 Colis reçus</h1>
        <p class="sous-titre">Liste complète de tous les colis enregistrés</p>

        <!-- FILTRES -->
        <form method="get" class="filtre">
            <label>Filtrer par statut :</label>
            <select name="statut" onchange="this.form.submit()">
                <option value="">Tous</option>
                <?php foreach ($statuts as $s): ?>
                    <option value="<?= $s['id_statut'] ?>"
                        <?= isset($_GET["statut"]) && $_GET["statut"] == $s["id_statut"] ? "selected" : "" ?>>
                        <?= $s["libelle"] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>


        <!-- TABLEAU -->
        <table class="tableau">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Département</th>
                    <th>N° commande</th>
                    <th>N° suivi</th>
                    <th>Date réception</th>
                    <th>Statut</th>
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
                    <td>
                        <?php if ($c["statut_id"] == 1): ?>
                            <span class="badge badge-attente">En attente</span>

                        <?php elseif ($c["statut_id"] == 2): ?>
                            <span class="badge badge-livre">Livré</span>

                        <?php elseif ($c["statut_id"] == 3): ?>
                            <span class="badge badge-retire">Retiré</span>

                        <?php elseif ($c["statut_id"] == 4): ?>
                            <span class="badge badge-nonid">Non identifié</span>

                        <?php else: ?>
                            <span class="badge badge-autre">Autre</span>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </main>

</body>
</html>