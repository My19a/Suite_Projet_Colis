<?php
$titre = 'Bons de commande – Service Financier';
$actif = '/finance/bons-commande';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Commandes</h1>
            <p class="page-subtitle">Historiques complets des commandes</p>
        </div>
    </div>

    <?php if (empty($bons)): ?>
        <?= etatVide('commandes', 'Aucun bon de commande', 'Aucun bon de commande à afficher.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($bons as $b): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('commandes', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($b["numero_commande"]) ?></div>
                            <div class="cl-sous">Bon de commande</div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $b["date_commande"] ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($b["montant_estime"], 2, ',', ' ') ?> EUR</span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($b["statut"]) ?>"><?= htmlspecialchars(libelleStatut($b["statut"])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
