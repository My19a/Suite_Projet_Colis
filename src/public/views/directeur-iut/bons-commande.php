<?php
$titre = 'Bons de commande – Directeur';
$actif = '/directeur/bons-commande';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Bons de commande</h1>
            <p class="page-subtitle">Historique des bons de commande validés</p>
        </div>
    </div>

    <?php if (empty($bons)): ?>
        <div class="vide-cadre">Aucun bon de commande</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($bons as $b): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('commandes', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($b["numero_commande"]) ?></div>
                            <div class="cl-sous">Bon #<?= $b["id_bon_commande"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Objet</span><span class="cl-val"><?= htmlspecialchars($b["objet"] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= $b["montant_estime"] ? number_format($b["montant_estime"], 2, ',', ' ') . " EUR" : "—" ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $b["date_commande"] ?></span></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
