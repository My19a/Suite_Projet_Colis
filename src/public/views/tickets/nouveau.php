<?php
$titre = 'Signaler un problème';
$actif = '/tickets';
$feuillesDeStyle = ['/assets/css/style-tickets.css'];
require __DIR__ . '/../partials/header.php';
?>

<div class="formulaire-page">
        <div class="formulaire-entete">
            <h1 class="formulaire-titre">Signaler un problème</h1>
            <p class="formulaire-sous-titre">Décrivez votre problème, l'équipe support vous répondra ici même.</p>
        </div>

        <form method="POST" action="/tickets/creer" class="formulaire">
            <div class="formulaire-partie">
                <div class="champ">
                    <label for="sujet" class="etiquette requis">Sujet</label>
                    <input type="text" id="sujet" name="sujet" class="saisie" maxlength="150"
                           placeholder="Ex: Le bouton de validation du devis ne répond pas"
                           value="<?= e($ancien['sujet'] ?? '') ?>" required>
                    <?php if (isset($erreurs['sujet'])): ?>
                        <div class="erreur-champ"><?= e($erreurs['sujet']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="champs-en-ligne">
                    <div class="champ">
                        <label for="categorie" class="etiquette">Catégorie</label>
                        <select id="categorie" name="categorie" class="liste-deroulante">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat) ?>" <?= ($ancien['categorie'] ?? '') === $cat ? 'selected' : '' ?>>
                                    <?= ucfirst(e($cat)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="champ">
                        <label for="priorite" class="etiquette">Priorité</label>
                        <select id="priorite" name="priorite" class="liste-deroulante">
                            <?php foreach ($priorites as $p): ?>
                                <option value="<?= e($p) ?>" <?= ($ancien['priorite'] ?? 'normale') === $p ? 'selected' : '' ?>>
                                    <?= e(libellePriorite($p)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="champ">
                    <label for="description" class="etiquette requis">Description</label>
                    <textarea id="description" name="description" class="saisie"
                              placeholder="Expliquez ce qui ne fonctionne pas, les étapes pour reproduire le problème, etc."
                              required><?= e($ancien['description'] ?? '') ?></textarea>
                    <small class="aide-champ">10 caractères minimum.</small>
                    <?php if (isset($erreurs['description'])): ?>
                        <div class="erreur-champ"><?= e($erreurs['description']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="formulaire-boutons">
                <button type="button" class="bouton bouton-secondaire" onclick="window.location.href='/tickets'">Annuler</button>
                <button type="submit" class="bouton bouton-principal">Envoyer le ticket</button>
            </div>
        </form>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
