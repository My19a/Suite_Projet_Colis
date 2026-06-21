<?php
$titre = 'Historique des devis – Éditeur de bons de commande';
$actif = '/finance/historique';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Historique des devis</h1>
            <p class="page-subtitle">Devis traités : approuvés (signés) et rejetés</p>
        </div>
    </div>

    <?php if (empty($devis)): ?>
        <?= etatVide('devis', 'Aucun devis traité', 'Les devis approuvés ou rejetés apparaîtront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($devis as $d): ?>
                <?php
                $estSigne = $d["statut"] === 'signe_directeur';
                $badge = $estSigne ? 'badge badge-valide_finance' : 'badge badge-rejete_finance';
                $label = $estSigne ? 'Approuvé (Signé)' : 'Rejeté';
                ?>
                <div class="carte-ligne cliquable" onclick="window.open('/finance/voir-devis?id=<?= $d["id_devis"] ?>', '_blank')">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d["objet"]) ?></div>
                            <div class="cl-sous">Devis #<?= $d["id_devis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($d["departement"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Demandeur</span><span class="cl-val"><?= htmlspecialchars($d["demandeur"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $d["date_demande"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= $badge ?>"><?= $label ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
