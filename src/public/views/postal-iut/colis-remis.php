<?php
$titre = 'Colis remis – Service Postal IUT';
$actif = '/postal/colis/remis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis remis</h1>
            <p class="page-subtitle">Colis retirés par les destinataires</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <?= etatVide('valide', 'Aucun colis remis', 'Les colis remis aux destinataires apparaîtront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <a class="carte-ligne" href="/postal/colis/details?id=<?= $c["id_colis"] ?>">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"] ?: "—") ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date retrait</span><span class="cl-val"><?= $c["date_retrait"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="badge badge-livre">Remis</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
