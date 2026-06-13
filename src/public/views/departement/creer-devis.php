<?php
$titre = 'Creer un devis – Département';
$actif = '/departement/creer-devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="largeur-moyenne">
        <div class="page-header-simple">
            <a href="/departement/dashboard" class="lien-retour">
                <span class="back-arrow">&larr;</span>
                Retour
            </a>
        </div>

        <div class="formulaire-page">
            <div class="formulaire-entete">
                <h1 class="formulaire-titre">Creer un Devis</h1>
                <p class="formulaire-sous-titre">Saisissez les informations du devis pour creer une demande d'achat</p>
            </div>

            <form method="POST" action="/departement/envoyer-devis" class="formulaire" id="devisForm">
                <div class="formulaire-partie">
                    <div class="champ">
                        <label for="objet" class="etiquette requis">Objet de la demande</label>
                        <input type="text" id="objet" name="objet" class="saisie" placeholder="Ex: Achat de matériel informatique pour le laboratoire" value="<?= htmlspecialchars($ancien['objet'] ?? '') ?>" required>
                        <small class="aide-champ">Décrivez brièvement l'objet de votre demande d'achat</small>
                        <?php if (isset($erreurs['objet'])): ?><div style="color:#dc2626;font-size:13px;margin-top:4px;"><?= htmlspecialchars($erreurs['objet']) ?></div><?php endif; ?>
                    </div>

                    <div class="champ">
                        <label for="fournisseur_id" class="etiquette requis">Fournisseur</label>
                        <select id="fournisseur_id" name="fournisseur_id" class="liste-deroulante" required>
                            <option value="">Sélectionnez un fournisseur</option>
                            <?php foreach ($fournisseurs as $fournisseur): ?>
                                <option value="<?= $fournisseur['id_fournisseur']; ?>" <?= (($ancien['fournisseur_id'] ?? '') == $fournisseur['id_fournisseur']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($fournisseur['nom']); ?>
                                    <?php if (!empty($fournisseur['contact_email'])): ?>
                                        - <?= htmlspecialchars($fournisseur['contact_email']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($erreurs['fournisseur'])): ?><div style="color:#dc2626;font-size:13px;margin-top:4px;"><?= htmlspecialchars($erreurs['fournisseur']) ?></div><?php endif; ?>
                    </div>

                    <div class="champ">
                        <label for="montant_estime" class="etiquette requis">Montant estime (EUR)</label>
                        <input type="number" id="montant_estime" name="montant_estime" class="saisie" placeholder="0.00" step="0.01" min="0" value="<?= htmlspecialchars($ancien['montant'] ?? '') ?>" required>
                        <small class="aide-champ">Montant estimé de la commande en euros</small>
                        <?php if (isset($erreurs['montant'])): ?><div style="color:#dc2626;font-size:13px;margin-top:4px;"><?= htmlspecialchars($erreurs['montant']) ?></div><?php endif; ?>
                    </div>
                </div>

                <div class="formulaire-boutons">
                    <button type="button" class="bouton bouton-secondaire" onclick="window.location.href='/departement/dashboard'">Annuler</button>
                    <button type="submit" class="bouton bouton-principal">Creer et envoyer le devis</button>
                </div>
            </form>

            <div class="etapes">
                <h3 class="etapes-titre">Detail de la validation</h3>
                <ol class="etapes-liste">
                    <li class="etape">
                        <span class="etape-numero">1</span>
                        <span class="etape-texte">Vous creez le devis</span>
                    </li>
                    <li class="etape">
                        <span class="etape-numero">2</span>
                        <span class="etape-texte">Le service financier vérifié le budget</span>
                    </li>
                    <li class="etape">
                        <span class="etape-numero">3</span>
                        <span class="etape-texte">Si validé, un bon de commande est créé</span>
                    </li>
                    <li class="etape">
                        <span class="etape-numero">4</span>
                        <span class="etape-texte">Le directeur signé le bon de commande</span>
                    </li>
                    <li class="etape">
                        <span class="etape-numero">5</span>
                        <span class="etape-texte">La commande est envoyee au fournisseur</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
