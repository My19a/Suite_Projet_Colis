<?php
$titre = 'Budget – Département';
$actif = '/departement/budget';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header-simple">
        <a href="/departement/dashboard" class="lien-retour">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Budget du département</h1>
            <p class="page-subtitle">Situation budgétaire actuelle</p>
        </div>
    </div>

    <div class="chiffres">
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Budget total</span>
            <div class="chiffre-valeur"><?= number_format($budget["budget_total"], 2, ',', ' ') ?></div>
            <div class="chiffre-info">EUR alloué</div>
        </div>

        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">Budget utilisé</span>
            <div class="chiffre-valeur"><?= number_format($budget["budget_utilise"], 2, ',', ' ') ?></div>
            <div class="chiffre-info">EUR dépensé</div>
        </div>

        <?php
            $restantB = $budget["budget_total"] - $budget["budget_utilise"];
            $cbB = classeBudget($restantB, $budget["budget_total"]);
            $chiffreB = ['budget-large' => 'chiffre-ok', 'budget-moyen' => 'chiffre-attn', 'budget-faible' => 'chiffre-err', 'budget-neutre' => ''][$cbB];
        ?>
        <div class="chiffre <?= $chiffreB ?>">
            <span class="chiffre-titre">Budget restant</span>
            <div class="chiffre-valeur"><?= number_format($restantB, 2, ',', ' ') ?></div>
            <div class="chiffre-info">EUR disponible</div>
        </div>
    </div>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Répartition du budget</h2>
        </div>

        <?php
        $total = $budget["budget_total"];
        $utilise = $budget["budget_utilise"];
        $pourcentage = $total > 0 ? round(($utilise / $total) * 100) : 0;
        ?>

        <div class="progression">
            <div class="progression-piste">
                <div class="progression-jauge <?= $cbB ?>" style="width: <?= $pourcentage ?>%;"></div>
            </div>
            <span class="progression-pourcent"><?= $pourcentage ?>%</span>
        </div>
        <div class="budget-bornes">
            <span><?= number_format($utilise, 2, ',', ' ') ?> EUR utilisés</span>
            <span><?= number_format($total, 2, ',', ' ') ?> EUR alloués</span>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
