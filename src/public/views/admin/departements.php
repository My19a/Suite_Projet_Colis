<?php
$titre = 'Départements – Admin';
$actif = '/admin/departements';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Gestion des départements</h1>
            <p class="page-subtitle">Consulter, modifier et supprimer les départements</p>
        </div>
        <a href="/admin/ajouter-departement" class="bouton bouton-principal"><?= icone('plus', 14) ?>Ajouter un département</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="message message-ok">
            Département supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="message message-err">
            Suppression impossible : ce département est encore lié à des données (utilisateurs, devis, bons de commande…). Réaffectez ou supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <?php if (empty($departements)): ?>
        <?= etatVide('departements', 'Aucun département', 'Ajoutez un département pour commencer.', '/admin/ajouter-departement', 'Ajouter un département') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($departements as $d): ?>
                <div class="carte-ligne cliquable" onclick="location.href='/admin/modifier-departement?id=<?= $d['id_departement'] ?>'">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('departements', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d['nom']) ?></div>
                            <div class="cl-sous">Département</div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Budget total</span><span class="cl-val"><?= number_format($d['budget_total'], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Budget utilisé</span><span class="cl-val"><?= number_format($d['budget_utilise'], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Budget restant</span><span class="cl-val <?= classeBudget($d['budget_total'] - $d['budget_utilise'], $d['budget_total']) ?>"><?= number_format($d['budget_total'] - $d['budget_utilise'], 2, ',', ' ') ?> EUR</span></div>
                    </div>
                    <div class="cl-fin" onclick="event.stopPropagation()">
                        <form method="post" action="/admin/supprimer-departement"
                              onsubmit="return confirm('Supprimer définitivement le département <?= htmlspecialchars($d['nom'], ENT_QUOTES) ?> ?');">
                            <input type="hidden" name="id_departement" value="<?= $d['id_departement'] ?>">
                            <button type="submit" class="btn-croix" title="Supprimer"><?= icone('croix', 16) ?></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
