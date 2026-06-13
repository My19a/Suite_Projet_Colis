<?php
$titre = 'Colis non identifiés – Postal IUT';
$actif = '/postal/colis/non-identifies';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis non identifiés</h1>
            <p class="page-subtitle">Colis reçus sans destinataire identifié</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <div class="vide-cadre">Aucun colis non identifié</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <div class="carte-ligne cliquable" onclick="location.href='/postal/colis/details?id=<?= $c["id_colis"] ?>'">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"] ?: "Sans suivi") ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Commentaire</span><span class="cl-val"><?= htmlspecialchars($c["commentaire"] ?: "—") ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <a href="/postal/colis/assigner?id=<?= $c["id_colis"] ?>" class="bouton bouton-petit bouton-principal" onclick="event.stopPropagation()">Assigner</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
