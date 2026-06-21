<?php
$titre = 'Historique colis – Responsable colis';
$actif = '/postal/historique';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Historique des actions</h1>
            <p class="page-subtitle">Traçabilité des transferts vers l'IUT et des réceptions par le destinataire</p>
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
                        <div class="cl-champ"><span class="cl-cle">Responsable colis</span><span class="cl-val"><?= htmlspecialchars($h["responsable"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Demandeur</span><span class="cl-val"><?= htmlspecialchars($h["demandeur"] ?? "—") ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="badge"><?= htmlspecialchars($h["action"]) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
