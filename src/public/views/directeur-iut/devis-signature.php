<?php
$titre = 'Signature devis – Directeur';
$actif = '/directeur/devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Signature du devis</h1>
            <p class="page-subtitle">Vérifier et signer le devis</p>
        </div>
    </div>

    <?php if (empty($devis)): ?>
        <div class="vide-cadre">Aucun devis à signer</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($devis as $d): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d["objet"]) ?></div>
                            <div class="cl-sous">Devis #<?= $d["id_devis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $d["date_demande"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <a class="bouton bouton-petit bouton-principal" href="/directeur/signer-devis?id=<?= $d["id_devis"] ?>">Signer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
