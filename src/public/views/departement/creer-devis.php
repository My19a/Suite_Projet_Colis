<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creer un devis – Departement</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Departement</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a href="/departement/dashboard">Tableau de bord</a>
        <a class="actif" href="/departement/creer-devis">Creer un devis</a>
        <a href="/departement/mes-devis">Mes devis</a>
        <a href="/departement/bons-commande">Mes bons de commande</a>
        <a href="/departement/mes-colis">Mes colis</a>
        <a href="/departement/budget">Budget</a>
        <a href="/departement/fournisseurs">Fournisseurs</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="create-devis-page">
        <div class="page-header-simple">
            <a href="/departement/dashboard" class="back-button-simple">
                <span class="back-arrow">&larr;</span>
                Retour
            </a>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title">Creer un Devis</h1>
                <p class="form-subtitle">Saisissez les informations du devis pour creer une demande d'achat</p>
            </div>

            <form method="POST" action="/departement/envoyer-devis" class="devis-form" id="devisForm">
                <div class="form-section">
                    <div class="form-group">
                        <label for="objet" class="form-label required">Objet de la demande</label>
                        <input type="text" id="objet" name="objet" class="form-input" placeholder="Ex: Achat de materiel informatique pour le laboratoire" required>
                        <small class="form-help">Decrivez brievement l'objet de votre demande d'achat</small>
                    </div>

                    <div class="form-group">
                        <label for="fournisseur_id" class="form-label required">Fournisseur</label>
                        <select id="fournisseur_id" name="fournisseur_id" class="form-select" required>
                            <option value="">Selectionnez un fournisseur</option>
                            <?php foreach ($fournisseurs as $fournisseur): ?>
                                <option value="<?= $fournisseur['id_fournisseur']; ?>">
                                    <?= htmlspecialchars($fournisseur['nom']); ?>
                                    <?php if (!empty($fournisseur['contact_email'])): ?>
                                        - <?= htmlspecialchars($fournisseur['contact_email']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="montant_estime" class="form-label required">Montant estime (EUR)</label>
                        <input type="number" id="montant_estime" name="montant_estime" class="form-input" placeholder="0.00" step="0.01" min="0" required>
                        <small class="form-help">Montant estime de la commande en euros</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='/departement/dashboard'">Annuler</button>
                    <button type="submit" class="btn btn-primary">Creer et envoyer le devis</button>
                </div>
            </form>

            <div class="process-section">
                <h3 class="process-title">Detail de la validation</h3>
                <ol class="process-list">
                    <li class="process-item">
                        <span class="process-number">1</span>
                        <span class="process-text">Vous creez le devis</span>
                    </li>
                    <li class="process-item">
                        <span class="process-number">2</span>
                        <span class="process-text">Le service financier verifie le budget</span>
                    </li>
                    <li class="process-item">
                        <span class="process-number">3</span>
                        <span class="process-text">Si valide, un bon de commande est cree</span>
                    </li>
                    <li class="process-item">
                        <span class="process-number">4</span>
                        <span class="process-text">Le directeur signe le bon de commande</span>
                    </li>
                    <li class="process-item">
                        <span class="process-number">5</span>
                        <span class="process-text">La commande est envoyee au fournisseur</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>

</main>

</body>
</html>
