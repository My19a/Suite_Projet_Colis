<?php
$titre = 'Budgets – Service Financier';
$actif = '/finance/budgets';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Budgets des départements</h1>
            <p class="page-subtitle">Suivi budgétaire global</p>
        </div>
    </div>

    <?php if (empty($budgets)): ?>
        <div class="vide-cadre">Aucun budget trouvé</div>
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
                        <div class="cl-champ"><span class="cl-cle">Restant</span><span class="cl-val montant"><?= number_format($b["budget_restant"], 2, ',', ' ') ?> EUR</span></div>
                    </div>
                    <div class="cl-fin">
                        <?php if ($b["budget_restant"] < 0): ?>
                            <span class="badge badge-refuse">Dépassé</span>
                        <?php else: ?>
                            <span class="badge badge-valide">OK</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
