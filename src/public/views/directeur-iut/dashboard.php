<?php
$titre = 'Tableau de bord – Directeur';
$actif = '/directeur/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Validation et suivi des décisions financières</p>
        </div>
    </div>

    <div class="chiffres">
        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">Devis à signer</span>
            <div class="chiffre-valeur"><?= $stats["devis_attente"] ?></div>
            <div class="chiffre-info">En attente</div>
        </div>

        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">BC signés</span>
            <div class="chiffre-valeur"><?= $stats["bc_signes"] ?></div>
            <div class="chiffre-info">Bons de commande</div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Devis validés par le service financier</h2>
        <a href="/directeur/devis" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($devis)): ?>
        <?= etatVide('signature', 'Aucun devis à signer', 'Aucun devis n\'attend votre signature.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($devis as $d): ?>
                <div class="carte-ligne cliquable" onclick="window.open('/directeur/voir-devis?id=<?= $d["id_devis"] ?>', '_blank')">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d["objet"]) ?></div>
                            <div class="cl-sous">Devis #<?= $d["id_devis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Date demande</span><span class="cl-val"><?= $d["date_demande"] ?></span></div>
                    </div>
                    <div class="cl-fin" onclick="event.stopPropagation()">
                        <a class="bouton bouton-petit bouton-principal" href="/directeur/signer-devis?id=<?= $d["id_devis"] ?>">Signer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Historique des bons de commande</h2>
        <a href="/directeur/bons-commande" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($bons)): ?>
        <?= etatVide('commandes', 'Aucun bon de commande', 'Les bons de commande s\'afficheront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($bons as $b): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('commandes', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($b["numero_commande"]) ?></div>
                            <div class="cl-sous">Bon #<?= $b["id_bon_commande"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $b["date_commande"] ?></span></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
