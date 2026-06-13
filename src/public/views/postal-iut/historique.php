<?php
$titre = 'Historique global – Service Postal IUT';
$actif = '/postal/historique';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Historique global</h1>
            <p class="page-subtitle">Dernieres actions effectuées sur tous les colis</p>
        </div>
    </div>

    <?php if (empty($historique)): ?>
        <div class="vide-cadre">Aucun historique disponible</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($historique as $h): ?>
                <a class="carte-ligne" href="/postal/colis/details?id=<?= $h["id_colis"] ?>">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('historique', 19) ?></div>
                        <div>
                            <div class="cl-titre">Colis #<?= $h["id_colis"] ?></div>
                            <div class="cl-sous"><?= $h["date_action"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">N° commande</span><span class="cl-val"><?= htmlspecialchars($h["numero_commande"] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">N° suivi</span><span class="cl-val"><?= htmlspecialchars($h["numero_suivi"] ?: "—") ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="badge"><?= htmlspecialchars(joli($h["action"])) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
