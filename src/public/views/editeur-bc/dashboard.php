<?php
$titre = 'Dashboard – Éditeur de bons de commande';
$actif = '/finance/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Suivi budgétaire et validation des devis</p>
        </div>
    </div>

    <div class="chiffres">
        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">Devis en attente</span>
            <div class="chiffre-valeur"><?= $stats["devis_attente"] ?></div>
            <div class="chiffre-info">À vérifier</div>
        </div>

        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Bons de commande</span>
            <div class="chiffre-valeur"><?= $stats["bons_commande"] ?></div>
            <div class="chiffre-info">Total</div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Budgets des départements</h2>
        <a href="/finance/budgets" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($budgets)): ?>
        <?= etatVide('budget', 'Aucun budget', 'Les budgets des départements s\'afficheront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($budgets as $b): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('budget', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($b["nom"]) ?></div>
                            <div class="cl-sous">Département</div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Budget total</span><span class="cl-val"><?= number_format($b["budget_total"], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Utilisé</span><span class="cl-val"><?= number_format($b["budget_utilise"], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Restant</span><span class="cl-val montant"><?= number_format($b["budget_total"] - $b["budget_utilise"], 2, ',', ' ') ?> EUR</span></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Devis à vérifier</h2>
        <a href="/finance/devis" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($devis)): ?>
        <?= etatVide('devis', 'Aucun devis en attente', 'Aucun devis à vérifier pour le moment.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($devis as $d): ?>
                <div class="carte-ligne cliquable" onclick="window.open('/finance/voir-devis?id=<?= $d["id_devis"] ?>', '_blank')">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d["objet"]) ?></div>
                            <div class="cl-sous">Devis #<?= $d["id_devis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($d["departement"]) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></div>
                    </div>
                    <div class="cl-fin" onclick="event.stopPropagation()">
                        <a class="bouton bouton-petit bouton-valider" href="/finance/valider-devis?id=<?= $d["id_devis"] ?>">Valider</a>
                        <a class="bouton bouton-petit bouton-danger" href="/finance/rejeter-devis?id=<?= $d["id_devis"] ?>">Rejeter</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
