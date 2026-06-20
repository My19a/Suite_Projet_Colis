<?php
$titre = 'Responsable colis';
$actif = '/postal/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue d'ensemble des commandes enregistrées</p>
        </div>
        <a href="/postal/commandes" class="bouton bouton-principal">Voir les commandes</a>
    </div>

    <div class="chiffres">
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Commandes</span>
            <div class="chiffre-valeur"><?= $stats["bons_total"] ?></div>
            <div class="chiffre-info">Créés par l'éditeur</div>
        </div>

        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">À réceptionner</span>
            <div class="chiffre-valeur"><?= $stats["a_receptionner"] ?></div>
            <div class="chiffre-info">Avec colis attendus</div>
        </div>

        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">À transférer</span>
            <div class="chiffre-valeur"><?= $stats["a_transferer"] ?></div>
            <div class="chiffre-info">Reçus à l'université</div>
        </div>

        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Transférés</span>
            <div class="chiffre-valeur"><?= $stats["transferes"] ?></div>
            <div class="chiffre-info">Envoyés à l'IUT</div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Commandes en attente</h2>
        <a href="/postal/commandes" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($commandes_a_receptionner)): ?>
        <?= etatVide('commandes', 'Aucune commande en attente', 'Toutes les commandes ont été traitées.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($commandes_a_receptionner as $bc): ?>
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

    <div class="bloc-entete">
        <h2 class="bloc-titre">Derniers colis reçus</h2>
        <a href="/postal/colis" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($colis_recents)): ?>
        <?= etatVide('reception', 'Aucun colis reçu', 'Les colis reçus à l\'université s\'afficheront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis_recents as $c): ?>
                <a class="carte-ligne" href="/postal/colis">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"]) ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Bon de commande</span><span class="cl-val"><?= htmlspecialchars($c["numero_commande"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Demandeur</span><span class="cl-val"><?= htmlspecialchars($c["demandeur"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?? "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                        
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($c["statut"]) ?>"><?= htmlspecialchars(libelleStatut($c["statut"])) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
