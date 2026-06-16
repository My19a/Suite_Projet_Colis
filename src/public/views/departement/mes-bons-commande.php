<?php
$titre = 'Mes bons de commande – Département';
$actif = '/departement/bons-commande';
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
            <h1 class="page-title">Mes Bons de Commande</h1>
            <p class="page-subtitle">Historique complet de vos commandes</p>
        </div>
    </div>

    <div class="recherche">
        <input type="text" class="recherche-saisie" placeholder="Rechercher par numéro, fournisseur ou statut..." id="searchCommandes" onkeyup="filterCommandes()">
        <button type="button" class="btn-loupe" onclick="filterCommandes()" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </div>

    <?php
    $totalCommandes = isset($bons) ? count($bons) : 0;
    $enAttente = 0;
    $signes = 0;
    $montantTotal = 0;
    if (isset($bons)) {
        foreach ($bons as $c) {
            if ($c['statut'] === 'en_preparation') $enAttente++;
            if ($c['statut'] === 'signe') $signes++;
            $montantTotal += $c['montant_estime'];
        }
    }
    ?>

    <div class="chiffres">
        <div class="chiffre">
            <span class="chiffre-titre">Total commandes</span>
            <div class="chiffre-valeur"><?= $totalCommandes ?></div>
        </div>
        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">En attente</span>
            <div class="chiffre-valeur"><?= $enAttente ?></div>
        </div>
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Signés</span>
            <div class="chiffre-valeur"><?= $signes ?></div>
        </div>
        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Montant total</span>
            <div class="chiffre-valeur" style="font-size: 24px;"><?= number_format($montantTotal, 2, ',', ' ') ?> EUR</div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Liste des bons de commande</h2>
        <span class="bloc-sous-titre"><?= $totalCommandes ?> bon(s) de commande trouvé(s)</span>
    </div>

    <?php if (empty($bons)): ?>
        <?= etatVide('commandes', 'Aucun bon de commande', 'Vos bons de commande apparaîtront ici.') ?>
    <?php else: ?>
        <div class="liste" id="commandesTable">
            <?php foreach ($bons as $cmd): ?>
                <div class="carte-ligne commande-row">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('commandes', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($cmd['numero_commande']) ?></div>
                            <div class="cl-sous"><?= date('d/m/Y', strtotime($cmd['date_commande'])) ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Fournisseur</span><span class="cl-val"><?= htmlspecialchars($cmd['fournisseur_nom']) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant</span><span class="cl-val montant"><?= number_format($cmd['montant_estime'], 2, ',', ' ') ?> EUR</span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($cmd['statut']) ?>"><?= htmlspecialchars(libelleStatut($cmd['statut'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<script>
function filterCommandes() {
    const filter = document.getElementById('searchCommandes').value.toLowerCase();
    for (let row of document.getElementsByClassName('commande-row')) {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
