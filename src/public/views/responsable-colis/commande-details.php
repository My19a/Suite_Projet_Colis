<?php
$titre = 'Détail commande';
$actif = '/postal/commandes';
$colisAttendus = array_filter($commande["colis"], fn($c) => (int) $c["statut_id"] === 3);
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title"><?= htmlspecialchars($commande["numero_commande"]) ?></h1>
        <p class="page-subtitle"><?= htmlspecialchars($commande["objet"] ?: $commande["commentaire"] ?: "Commande sans objet") ?></p>
    </div>
    <a href="/postal/commandes" class="bouton bouton-secondaire">Retour</a>
</div>

<div class="message message-info">
    <span class="message-corps">Consultation seule. La réception se déclare depuis l'onglet « Réception d'un colis » (numéro de suivi + demandeur).</span>
</div>

<div class="chiffres">
    <div class="chiffre chiffre-info-c">
        <span class="chiffre-titre">Colis rattachés</span>
        <div class="chiffre-valeur"><?= count($commande["colis"]) ?></div>
        <div class="chiffre-info">Total</div>
    </div>
    <div class="chiffre chiffre-attn">
        <span class="chiffre-titre">En attente</span>
        <div class="chiffre-valeur"><?= count($colisAttendus) ?></div>
        <div class="chiffre-info">À réceptionner</div>
    </div>
    <div class="chiffre chiffre-ok">
        <span class="chiffre-titre">Montant</span>
        <div class="chiffre-valeur"><?= number_format((float) $commande["montant_estime"], 0, ',', ' ') ?></div>
        <div class="chiffre-info">EUR</div>
    </div>
</div>

<div class="bloc">
    <div class="cl-champs">
        <div class="cl-champ"><span class="cl-cle">Fournisseur</span><span class="cl-val"><?= htmlspecialchars($commande["fournisseur"] ?: "—") ?></span></div>
        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($commande["departement"] ?: "—") ?></span></div>
        <div class="cl-champ"><span class="cl-cle">Demandeur</span><span class="cl-val"><?= htmlspecialchars($commande["demandeur"] ?: "—") ?></span></div>
        <div class="cl-champ"><span class="cl-cle">Livraison estimée</span><span class="cl-val"><?= $commande["date_estimee_livraison"] ? date('d/m/Y', strtotime($commande["date_estimee_livraison"])) : "—" ?></span></div>
    </div>
</div>

<div class="bloc-entete">
    <h2 class="bloc-titre">Colis de la commande</h2>
</div>

<div class="liste">
    <?php foreach ($commande["colis"] as $c): ?>
        <div class="carte-ligne">
            <div class="cl-tete">
                <div class="cl-icone"><?= icone('colis', 19) ?></div>
                <div>
                    <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"] ?: "—") ?></div>
                    <div class="cl-sous"><?= nl2br(htmlspecialchars(trim($c["commentaire"] ?? ""))) ?></div>
                </div>
            </div>
            <div class="cl-champs">
                <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?: "—" ?></span></div>
            </div>
            <div class="cl-fin">
                <span class="<?= badgeStatut($c["statut"]) ?>"><?= htmlspecialchars(libelleStatut($c["statut"])) ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
