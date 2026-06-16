<?php
$titre = 'Colis reçus – Postal IUT';
$actif = '/postal/colis/recus';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis reçus à l'IUT</h1>
            <p class="page-subtitle">Colis transférés depuis l'université</p>
        </div>
    </div>

    <?php if (empty($colis)): ?>
        <div class="vide-cadre">Aucun colis reçu</div>
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
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "Non identifié") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($c["statut"]) ?>"><?= htmlspecialchars(libelleStatut($c["statut"])) ?></span>
                        <a class="bouton bouton-petit bouton-valider" href="/postal/colis/retirer?id=<?= $c["id_colis"] ?>" onclick="event.stopPropagation()">Retiré</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
