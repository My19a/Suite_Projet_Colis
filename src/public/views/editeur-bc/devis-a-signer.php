<?php
$titre = 'Devis à signer – Éditeur de bons de commande';
$actif = '/directeur/devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Devis à signer</h1>
            <p class="page-subtitle">Devis vérifiés, prêts à être signés</p>
        </div>
    </div>

    <?php if (empty($devis)): ?>
        <?= etatVide('signature', 'Aucun devis à signer', 'Aucun devis en attente de signature.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($devis as $d): ?>
                <div class="carte-ligne cliquable" onclick="window.open('/directeur/voir-devis?id=<?= $d["id_devis"] ?>', '_blank')">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d["objet"]) ?></div>
                            <div class="cl-sous">Devis #<?= $d["id_devis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Montant estimé</span><span class="cl-val montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($d["departement"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date demande</span><span class="cl-val"><?= $d["date_demande"] ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Demandeur</span><span class="cl-val"><?= htmlspecialchars($d["demandeur"] ?? "—") ?></span></div>
                    </div>
                    <div class="cl-fin" onclick="event.stopPropagation()">
                        <a class="bouton bouton-petit bouton-principal" href="/directeur/signer-devis?id=<?= $d["id_devis"] ?>">Signer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
