<?php
$titre = 'Liste des colis – Postal Université';
$actif = '/postal-univ/colis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Liste des colis reçus</h1>
            <p class="page-subtitle">Tous les colis réceptionnés par l'université</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <div class="vide-cadre">Aucun colis</div>
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
                        <div class="cl-champ"><span class="cl-cle">Campus / IUT</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "Non identifié") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($c["statut"]) ?>"><?= htmlspecialchars(joli($c["statut"])) ?></span>
                        <?php if ($c["statut_id"] == 1): ?>
                            <a class="bouton bouton-petit bouton-principal" href="/postal-univ/transferer?id=<?= $c["id_colis"] ?>">Transférer vers IUT</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
