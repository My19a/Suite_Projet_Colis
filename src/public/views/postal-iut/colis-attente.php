<?php
$titre = 'Colis en attente – Service Postal IUT';
$actif = '/postal/colis/attente';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis en attente</h1>
            <p class="page-subtitle">Tous les colis reçus mais non encore livrés</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <?= etatVide('historique', 'Aucun colis en attente', 'Aucun colis n\'attend de retrait.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <a class="carte-ligne" href="/postal/colis/details?id=<?= $c["id_colis"] ?>">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"]) ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "Non identifié") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">N° commande</span><span class="cl-val"><?= htmlspecialchars($c["numero_commande"]) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
