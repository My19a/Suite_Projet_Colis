<?php
$titre = 'Confirmation des colis – Postal IUT';
$actif = '/postal/confirmation';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Confirmation des colis</h1>
            <p class="page-subtitle">Colis transférés par le service postal universitaire et en attente de confirmation à l'IUT</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <?= etatVide('confirmation', 'Aucun colis à confirmer', 'Tous les colis reçus ont été confirmés.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <div class="carte-ligne cliquable" onclick="location.href='/postal/colis/details?id=<?= $c["id_colis"] ?>'">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"]) ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Bon de commande</span><span class="cl-val"><?= htmlspecialchars($c["numero_commande"]) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date transfert</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                    </div>
                    <div class="cl-fin" onclick="event.stopPropagation()">
                        <a class="bouton bouton-petit bouton-principal" href="/postal/confirmer?id=<?= $c["id_colis"] ?>">Confirmer réception</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
