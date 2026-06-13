<?php
$titre = 'Bons de commande – Admin';
$actif = '/admin/commandes';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Bons de commande</h1>
            <p class="page-subtitle">Liste de tous les bons de commande</p>
        </div>
    </div>

    <div class="bloc">
        <div class="chiffres">
            <?php foreach ($stats as $statut => $count): ?>
            <div class="chiffre">
                <div class="chiffre-valeur"><?= $count ?></div>
                <div class="chiffre-titre"><?= htmlspecialchars(joli($statut)) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <form method="get" class="recherche">
        <input type="text" name="q" class="recherche-saisie" placeholder="Rechercher par numéro…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn-loupe" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </form>

    <?php if (empty($commandes)): ?>
        <div class="vide-cadre">Aucune commande</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($commandes as $c): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('commandes', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c['numero_commande']) ?></div>
                            <div class="cl-sous">Bon de commande</div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c['departement'] ?? '—') ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Fournisseur</span><span class="cl-val"><?= htmlspecialchars($c['fournisseur'] ?? '—') ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($c['montant_estime'] ?? 0, 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $c['date_commande'] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($c['statut']) ?>"><?= htmlspecialchars(joli($c['statut'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
