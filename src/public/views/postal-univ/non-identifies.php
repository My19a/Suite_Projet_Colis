<?php
$titre = 'Colis non identifiés – Postal Université';
$actif = '/postal-univ/non-identifies';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis non identifiés</h1>
            <p class="page-subtitle">Colis sans correspondance ou information incomplète</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <?= etatVide('alerte', 'Aucun colis non identifié', 'Tous les colis ont été identifiés.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"] ?: "Sans suivi") ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="badge badge-non_identifie"><?= htmlspecialchars(libelleStatut($c["statut"])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
