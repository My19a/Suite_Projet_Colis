<?php
$titre = 'Tous les devis – Admin';
$actif = '/admin/devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tous les devis</h1>
            <p class="page-subtitle">Vue globale de l'ensemble des devis du système</p>
        </div>
    </div>

    <?php if (!empty($stats)): ?>
    <div class="chiffres">
        <?php foreach ($stats as $s): ?>
        <div class="chiffre">
            <div class="chiffre-valeur"><?= $s['total'] ?></div>
            <div class="chiffre-titre"><?= htmlspecialchars(libelleStatutDevis($s['statut'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="get" class="recherche">
        <input type="text" name="q" class="recherche-saisie" placeholder="Rechercher un devis…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn-loupe" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </form>

    <?php if (empty($devis)): ?>
        <?= etatVide('devis', 'Aucun devis', 'Aucun devis ne correspond à votre recherche.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($devis as $d): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d['objet']) ?></div>
                            <div class="cl-sous">Devis #<?= $d['id_devis'] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($d['departement'] ?? '—') ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Fournisseur</span><span class="cl-val"><?= htmlspecialchars($d['fournisseur'] ?? '—') ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($d['montant_estime'], 2, ',', ' ') ?> EUR</span></div>
                        <div class="cl-champ"><span class="cl-cle">Date</span><span class="cl-val"><?= $d['date_demande'] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($d['statut']) ?>"><?= htmlspecialchars(libelleStatutDevis($d['statut'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
