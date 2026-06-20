<?php
$titre = 'Colis à transférer – Responsable colis';
$actif = '/postal/colis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis à transférer</h1>
            <p class="page-subtitle">Colis déclarés reçus à l'université, prêts à être transférés vers l'IUT</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <?= etatVide('colis', 'Aucun colis à transférer', 'Les colis réceptionnés à l’université apparaîtront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"] ?: "—") ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">N° bon de commande</span><span class="cl-val"><?= htmlspecialchars($c["numero_commande"]) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($c["statut"]) ?>"><?= htmlspecialchars(libelleStatut($c["statut"])) ?></span>
                        <?php if ($c["statut_id"] == 1): ?>
                            <a class="bouton bouton-petit bouton-principal" href="/postal/transferer?id=<?= $c["id_colis"] ?>" onclick="return confirm('Confirmer le transfert à l\'IUT de ce colis ?\nUn e-mail de notification sera envoyé au demandeur.');">Transférer vers IUT</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
