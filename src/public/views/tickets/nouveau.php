<?php
$titre = 'Signaler un probleme';
$actif = '/tickets';
$feuillesDeStyle = ['/assets/css/style-tickets.css'];
require __DIR__ . '/../partials/header.php';
?>

<div class="form-container">
        <div class="form-header">
            <h1 class="form-title">Signaler un probleme</h1>
            <p class="form-subtitle">Decrivez votre probleme, l'equipe support vous repondra ici meme.</p>
        </div>

        <form method="POST" action="/tickets/creer" class="devis-form">
            <div class="form-section">
                <div class="form-group">
                    <label for="sujet" class="form-label required">Sujet</label>
                    <input type="text" id="sujet" name="sujet" class="form-input" maxlength="150"
                           placeholder="Ex: Le bouton de validation du devis ne repond pas"
                           value="<?= e($ancien['sujet'] ?? '') ?>" required>
                    <?php if (isset($erreurs['sujet'])): ?>
                        <div class="form-error"><?= e($erreurs['sujet']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categorie" class="form-label">Categorie</label>
                        <select id="categorie" name="categorie" class="form-select">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat) ?>" <?= ($ancien['categorie'] ?? '') === $cat ? 'selected' : '' ?>>
                                    <?= ucfirst(e($cat)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priorite" class="form-label">Priorite</label>
                        <select id="priorite" name="priorite" class="form-select">
                            <?php foreach ($priorites as $p): ?>
                                <option value="<?= e($p) ?>" <?= ($ancien['priorite'] ?? 'normale') === $p ? 'selected' : '' ?>>
                                    <?= ucfirst(e($p)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label required">Description</label>
                    <textarea id="description" name="description" class="form-input"
                              placeholder="Expliquez ce qui ne fonctionne pas, les etapes pour reproduire le probleme, etc."
                              required><?= e($ancien['description'] ?? '') ?></textarea>
                    <small class="form-help">10 caracteres minimum.</small>
                    <?php if (isset($erreurs['description'])): ?>
                        <div class="form-error"><?= e($erreurs['description']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/tickets'">Annuler</button>
                <button type="submit" class="btn btn-primary">Envoyer le ticket</button>
            </div>
        </form>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
