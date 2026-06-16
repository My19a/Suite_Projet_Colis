<?php
$titre = 'Dashboard – Département';
$actif = '/departement/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Gérez vos devis, commandes et colis</p>
        </div>
        <button class="bouton bouton-principal" onclick="window.location.href='/departement/creer-devis'">
            <?= icone('plus', 14) ?>Créer un devis
        </button>
    </div>

    <div class="chiffres">
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Colis total</span>
            <div class="chiffre-valeur"><?php echo $stats['colis_total']; ?></div>
            <div class="chiffre-info">Total des colis</div>
        </div>

        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">Colis en attente</span>
            <div class="chiffre-valeur"><?php echo $stats['en_attente']; ?></div>
            <div class="chiffre-info">À récupérer</div>
        </div>

        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Colis retirés</span>
            <div class="chiffre-valeur"><?php echo $stats['retire']; ?></div>
            <div class="chiffre-info">Réceptions confirmées</div>
        </div>
    </div>

    <?php if (isset($budget)): ?>
    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Budget du département</h2>
            <span class="bloc-sous-titre">Situation budgétaire</span>
        </div>

        <?php
            $pct = $budget['budget_total'] > 0 ? round(($budget['budget_utilise'] / $budget['budget_total']) * 100) : 0;
            $cb  = classeBudget($budget['budget_restant'], $budget['budget_total']);
        ?>
        <div class="chiffres" style="margin-bottom: 14px;">
            <div class="chiffre">
                <span class="chiffre-titre">Budget total</span>
                <div class="chiffre-valeur" style="font-size: 24px;"><?php echo number_format($budget['budget_total'], 2, ',', ' '); ?> EUR</div>
            </div>
            <div class="chiffre">
                <span class="chiffre-titre">Budget utilisé</span>
                <div class="chiffre-valeur" style="font-size: 24px;"><?php echo number_format($budget['budget_utilise'], 2, ',', ' '); ?> EUR</div>
            </div>
            <div class="chiffre">
                <span class="chiffre-titre">Budget restant</span>
                <div class="chiffre-valeur <?= $cb ?>" style="font-size: 24px;"><?php echo number_format($budget['budget_restant'], 2, ',', ' '); ?> EUR</div>
            </div>
        </div>
        <div class="progression">
            <div class="progression-piste">
                <div class="progression-jauge <?= $cb ?>" style="width: <?= $pct ?>%;"></div>
            </div>
            <span class="progression-pourcent"><?= $pct ?>%</span>
        </div>
    </div>
    <?php endif; ?>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Derniers colis</h2>
        <span class="bloc-sous-titre">Suivez vos livraisons récentes</span>
        <a href="/departement/mes-colis" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($colis)): ?>
        <?= etatVide('colis', 'Aucun colis', 'Vos derniers colis s\'afficheront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $col): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($col['numero_suivi'] ?? 'N/A') ?></div>
                            <div class="cl-sous"><?= htmlspecialchars($col['numero_commande'] ?? '—') ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Destinataire</span><span class="cl-val"><?= htmlspecialchars($col['destinataire_nom'] ?? 'Non assigné') ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= isset($col['date_reception']) ? date('d/m/Y', strtotime($col['date_reception'])) : 'N/A' ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($col['statut_libelle']) ?>"><?= htmlspecialchars(libelleStatut($col['statut_libelle'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
