<?php
$titre = 'Tous les colis – Admin';
$actif = '/admin/colis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tous les colis</h1>
            <p class="page-subtitle">Vision globale et traçabilité complète des colis</p>
        </div>
    </div>

    <?php if (!empty($stats)): ?>
    <div class="chiffres">
        <?php foreach ($stats as $s): ?>
        <div class="chiffre">
            <div class="chiffre-valeur"><?= $s['total'] ?></div>
            <div class="chiffre-titre"><?= $s['statut'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="get" class="recherche">
        <input type="text" name="q" class="recherche-saisie" placeholder="N° suivi, BC, département, statut…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn-loupe" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </form>

    <?php if (empty($colis)): ?>
        <div class="vide-cadre">Aucun colis trouvé</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c['numero_suivi'] ?: '—') ?></div>
                            <div class="cl-sous">Colis #<?= $c['id_colis'] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Bon de commande</span><span class="cl-val"><?= htmlspecialchars($c['numero_commande'] ?: '—') ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c['departement'] ?: '—') ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Réception</span><span class="cl-val"><?= $c['date_reception'] ?: '—' ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Retrait</span><span class="cl-val"><?= $c['date_retrait'] ?: '—' ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($c['statut']) ?>"><?= htmlspecialchars(joli($c['statut'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
