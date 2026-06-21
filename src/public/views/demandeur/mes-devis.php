<?php
$titre = 'Mes devis – Département';
$actif = '/departement/mes-devis';
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
            <h1 class="page-title">Mes Devis</h1>
            <p class="page-subtitle">Historique complet de vos devis</p>
        </div>
        <button class="bouton bouton-principal" onclick="window.location.href='/departement/creer-devis'">Nouveau devis</button>
    </div>

    <div class="recherche">
        <input type="text" class="recherche-saisie" placeholder="Rechercher par objet, fournisseur ou statut..." id="searchDevis" onkeyup="filterDevis()">
        <button type="button" class="btn-loupe" onclick="filterDevis()" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </div>

    <?php
    $totalDevis = isset($devis) ? count($devis) : 0;
    $enAttente = 0;
    $valides = 0;
    $rejetes = 0;
    if (isset($devis)) {
        foreach ($devis as $d) {
            if ($d['statut'] === 'en_attente') $enAttente++;
            if (in_array($d['statut'], ['valide_finance', 'signe_directeur'])) $valides++;
            if ($d['statut'] === 'rejete_finance') $rejetes++;
        }
    }
    ?>

    <div class="chiffres">
        <div class="chiffre">
            <span class="chiffre-titre">Total devis</span>
            <div class="chiffre-valeur"><?= $totalDevis ?></div>
        </div>
        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">En attente</span>
            <div class="chiffre-valeur"><?= $enAttente ?></div>
        </div>
        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Validés</span>
            <div class="chiffre-valeur"><?= $valides ?></div>
        </div>
        <?php if ($rejetes > 0): ?>
        <div class="chiffre chiffre-err">
            <span class="chiffre-titre">Rejetés</span>
            <div class="chiffre-valeur"><?= $rejetes ?></div>
        </div>
        <?php endif; ?>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Liste des devis</h2>
        <span class="bloc-sous-titre"><?= $totalDevis ?> devis trouvé(s)</span>
    </div>

    <?php if (empty($devis)): ?>
        <?= etatVide('devis', 'Aucun devis', 'Créez un devis pour démarrer une commande.', '/departement/creer-devis', 'Créer un devis') ?>
    <?php else: ?>
        <div class="liste" id="devisTable">
            <?php foreach ($devis as $d): ?>
                <div class="carte-ligne devis-row">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('devis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($d['objet']) ?></div>
                            <div class="cl-sous"><?= date('d/m/Y', strtotime($d['date_demande'])) ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Fournisseur</span><span class="cl-val"><?= htmlspecialchars($d['fournisseur_nom']) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Montant estimé</span><span class="cl-val montant"><?= number_format($d['montant_estime'], 2, ',', ' ') ?> EUR</span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($d['statut']) ?>"><?= htmlspecialchars(libelleStatutDevis($d['statut'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<script>
function filterDevis() {
    const filter = document.getElementById('searchDevis').value.toLowerCase();
    for (let row of document.getElementsByClassName('devis-row')) {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
