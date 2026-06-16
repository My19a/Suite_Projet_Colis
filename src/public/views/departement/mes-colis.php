<?php
$titre = 'Mes colis – Département';
$actif = '/departement/mes-colis';
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
            <h1 class="page-title">Mes Colis</h1>
            <p class="page-subtitle">Suivi de vos livraisons</p>
        </div>
    </div>

    <div class="recherche">
        <input type="text" class="recherche-saisie" placeholder="Rechercher par numéro de suivi, BC ou statut..." id="rechercheColis" onkeyup="filtrerColis()">
        <button type="button" class="btn-loupe" onclick="filtrerColis()" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </div>

    <?php
    $totalColis = count($colis);
    $enTransit = 0;
    $enAttente = 0;
    $livres = 0;

    foreach ($colis as $c) {
        if ($c['statut'] === 'Transfere vers IUT') $enTransit++;
        if ($c['statut'] === 'En attente') $enAttente++;
        if ($c['statut'] === 'Livre') $livres++;
    }
    ?>

    <div class="chiffres">
        <div class="chiffre">
            <span class="chiffre-titre">Total colis</span>
            <div class="chiffre-valeur"><?= $totalColis ?></div>
        </div>
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">En transit</span>
            <div class="chiffre-valeur"><?= $enTransit ?></div>
        </div>
        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">En attente</span>
            <div class="chiffre-valeur"><?= $enAttente ?></div>
        </div>
        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Livrés</span>
            <div class="chiffre-valeur"><?= $livres ?></div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Liste des colis</h2>
        <span class="bloc-sous-titre"><?= $totalColis ?> colis trouvé(s)</span>
    </div>

    <?php if (empty($colis)): ?>
        <div class="vide-cadre">Aucun colis trouvé</div>
    <?php else: ?>
        <div class="liste" id="grilleColis">
            <?php foreach ($colis as $c): ?>
                <?php $statutAffichage = $c['statut'] === 'Retire' ? 'Livre' : $c['statut']; ?>
                <div class="carte-ligne ligne-colis">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c['numero_suivi'] ?? '—') ?></div>
                            <div class="cl-sous">N° de suivi</div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Bon de commande</span><span class="cl-val"><?= htmlspecialchars($c['numero_commande']) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c['date_reception'] ? date('d/m/Y', strtotime($c['date_reception'])) : '—' ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($statutAffichage) ?>"><?= htmlspecialchars(libelleStatut($statutAffichage)) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<script>
function filtrerColis() {
    const filter = document.getElementById('rechercheColis').value.toLowerCase();
    for (let carte of document.getElementsByClassName('ligne-colis')) {
        carte.style.display = carte.textContent.toLowerCase().includes(filter) ? '' : 'none';
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
