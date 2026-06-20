<?php
$titre = 'Détail commande';
$actif = '/postal/reception';
$colisAttendus = array_filter($commande["colis"], fn($c) => (int) $c["statut_id"] === 3);
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title"><?= htmlspecialchars($commande["numero_commande"]) ?></h1>
        <p class="page-subtitle"><?= htmlspecialchars($commande["objet"] ?: $commande["commentaire"] ?: "Commande sans objet") ?></p>
    </div>
    <a href="/postal/reception" class="bouton bouton-secondaire">Retour</a>
</div>

<?php if (isset($_GET["ok"])): ?>
    <div class="message message-ok">
        <span class="message-corps">Réception enregistrée. Les colis reçus sont maintenant disponibles dans “Colis à transférer”.</span>
    </div>
<?php endif; ?>

<div class="chiffres">
    <div class="chiffre chiffre-info-c">
        <span class="chiffre-titre">Colis rattachés</span>
        <div class="chiffre-valeur"><?= count($commande["colis"]) ?></div>
        <div class="chiffre-info">Total</div>
    </div>
    <div class="chiffre chiffre-attn">
        <span class="chiffre-titre">À réceptionner</span>
        <div class="chiffre-valeur"><?= count($colisAttendus) ?></div>
        <div class="chiffre-info">Université</div>
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

<form method="post" action="/postal/commande/receptionner">
    <input type="hidden" name="id_bon_commande" value="<?= (int) $commande["id_bon_commande"] ?>">

    <div class="bloc-entete">
        <h2 class="bloc-titre">Colis de la commande</h2>
        <?php if (!empty($colisAttendus)): ?>
            <button type="button" class="bouton bouton-secondaire bouton-petit" onclick="document.querySelectorAll('.case-colis').forEach(c => c.checked = true)">Tout sélectionner</button>
        <?php endif; ?>
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
                    <div class="cl-champ"><span class="cl-cle">Statut</span><span class="cl-val"><?= htmlspecialchars(libelleStatut($c["statut"])) ?></span></div>
                    <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?: "—" ?></span></div>
                </div>
                <div class="cl-fin">
                    <?php if ((int) $c["statut_id"] === 3): ?>
                        <label class="bouton bouton-petit bouton-secondaire">
                            <input class="case-colis" type="checkbox" name="colis_ids[]" value="<?= (int) $c["id_colis"] ?>">
                            Déclarer comme livré
                        </label>
                    <?php else: ?>
                        <span class="<?= badgeStatut($c["statut"]) ?>"><?= htmlspecialchars(libelleStatut($c["statut"])) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($colisAttendus)): ?>
        <div class="formulaire-boutons">
            <button type="submit" class="bouton bouton-principal">Déclarer la livraison</button>
        </div>
    <?php endif; ?>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
