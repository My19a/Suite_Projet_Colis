<?php
$titre = 'Historique – Postal Université';
$actif = '/postal-univ/historique';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Historique des actions</h1>
            <p class="page-subtitle">Traçabilité complète des colis</p>
        </div>
    </div>

    <?php if (empty($historique)): ?>
        <?= etatVide('historique', 'Aucun historique', 'Les actions sur les colis s\'afficheront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($historique as $h): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('historique', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($h["numero_suivi"] ?: "—") ?></div>
                            <div class="cl-sous">Colis #<?= $h["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $h["date_action"] ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Utilisateur</span><span class="cl-val"><?= htmlspecialchars($h["utilisateur"] ?? "—") ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="badge"><?= htmlspecialchars(joli($h["action"])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
