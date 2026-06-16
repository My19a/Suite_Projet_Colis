<?php
$titre = 'Devis à vérifier – Service Financier';
$actif = '/finance/devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Devis à vérifier</h1>
            <p class="page-subtitle">Devis soumis par les départements</p>
        </div>
    </div>

    <?php if (empty($devis)): ?>
        <?= etatVide('devis', 'Aucun devis à vérifier', 'Aucun devis en attente de vérification.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($devis as $d): ?>
                <div class="carte-ligne cliquable" onclick="window.open('/finance/voir-devis?id=<?= $d["id_devis"] ?>', '_blank')">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d["objet"]) ?></div>
                            <div class="cl-sous">Devis #<?= $d["id_devis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($d["departement"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $d["date_demande"] ?></span></div>
                    </div>
                    <div class="cl-fin" onclick="event.stopPropagation()">
                        <a class="bouton bouton-petit bouton-valider" href="/finance/valider-devis?id=<?= $d["id_devis"] ?>">Valider</a>
                        <a class="bouton bouton-petit bouton-danger" href="/finance/rejeter-devis?id=<?= $d["id_devis"] ?>">Rejeter</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
