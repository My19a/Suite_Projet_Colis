<?php
$titre = 'Commandes en attente';
$actif = '/postal/commandes';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title">Commandes en attente</h1>
        <p class="page-subtitle">Bons de commande déclarés par l'éditeur, en attente de réception (consultation seule)</p>
    </div>
</div>

<?php if (empty($commandes)): ?>
    <?= etatVide('commandes', 'Aucune commande à réceptionner', 'Les commandes validées par l’éditeur apparaîtront ici.') ?>
<?php else: ?>
    <div class="liste">
        <?php foreach ($commandes as $bc): ?>
            <a class="carte-ligne" href="/postal/commande?id=<?= (int) $bc["id_bon_commande"] ?>">
                <div class="cl-tete">
                    <div class="cl-icone"><?= icone('commandes', 19) ?></div>
                    <div>
                        <div class="cl-titre"><?= htmlspecialchars($bc["numero_commande"]) ?></div>
                        <div class="cl-sous"><?= htmlspecialchars($bc["fournisseur"] ?: "Fournisseur non renseigné") ?></div>
                    </div>
                </div>
                <div class="cl-champs">
                    <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($bc["departement"] ?: "—") ?></span></div>
                    <div class="cl-champ"><span class="cl-cle">Demandeur</span><span class="cl-val"><?= htmlspecialchars($bc["demandeur"] ?: "—") ?></span></div>
                    <div class="cl-champ"><span class="cl-cle">Colis attendus</span><span class="cl-val"><?= (int) $bc["colis_a_receptionner"] ?> / <?= (int) $bc["colis_total"] ?></span></div>
                    <div class="cl-champ"><span class="cl-cle">Livraison estimée</span><span class="cl-val"><?= $bc["date_estimee_livraison"] ? date('d/m/Y', strtotime($bc["date_estimee_livraison"])) : "—" ?></span></div>
                </div>
                <div class="cl-fin">
                    <span class="bouton bouton-petit bouton-principal">Voir détails</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
