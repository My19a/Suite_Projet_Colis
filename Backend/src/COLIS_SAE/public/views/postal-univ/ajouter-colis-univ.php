<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Colis - Postal UNIV</title>
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-dashboard.css">
    <link rel="stylesheet" href="/COLIS_SAE/assets/css/style-ajouter-colis.css">
</head>

<body class="tableau-bord">

    <!-- BARRE LATERALE -->
    <aside class="barre-laterale">
        <div class="entete-barre">
            <img src="/COLIS_SAE/assets/img/logo-iutv.png" alt="Logo" class="logo">
            <h2>Postal UNIV</h2>
            <p>Service Universitaire</p>
        </div>

        <nav class="menu">
            <a href="dashboard.php">📊 Tableau de bord</a>
            <a href="colis-recus-univ.php">📦 Colis reçus</a>
            <a href="colis-attente-transfert.php">⏳ En attente transfert</a>
            <a href="colis-transferes.php">✅ Colis transférés</a>
            <a href="recherche-colis-univ.php">🔍 Rechercher</a>
            <a href="ajouter-colis-univ.php" class="actif">➕ Ajouter un colis</a>
            <a href="historique-transferts.php">📜 Historique transferts</a>
        </nav>

        <div class="deconnexion">
            <a href="/COLIS_SAE/public/views/index.php">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="contenu">
        <h1>➕ Enregistrer un Nouveau Colis (UNIV)</h1>
        <p class="sous-titre">Enregistrer la réception d'un colis destiné à l'IUT</p>

        <?php if (isset($message)): ?>
            <div class="message">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-colis">
            
            <label for="numero_bc">Numéro de Bon de Commande </label>
            <input type="text" 
                   id="numero_bc" 
                   name="numero_bc" 
                   placeholder="" 
                   required>

            <label for="numero_suivi">Numéro de Suivi </label>
            <input type="text" 
                   id="numero_suivi" 
                   name="numero_suivi" 
                   placeholder="" 
                   required>

            <label for="commentaire">Commentaire (optionnel)</label>
            <textarea id="commentaire" 
                      name="commentaire" 
                      rows="4" 
                      placeholder=""></textarea>

            <button type="submit" class="btn-valider">✅ Enregistrer le colis</button>
        </form>

        <div style="margin-top: 25px; padding: 15px; background: #e3f2fd; border-radius: 10px; border-left: 4px solid #2196f3;">
            <h3 style="margin: 0 0 8px 0; color: #1565c0;">ℹ️ Information</h3>
            <p style="margin: 0; color: #0d47a1;">
                Le destinataire sera automatiquement identifié à partir du numéro de bon de commande. 
                Le colis sera ensuite prêt à être transféré vers le service postal IUT.
            </p>
        </div>

    </main>

</body>
</html>