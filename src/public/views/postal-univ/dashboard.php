<?php
$titre = 'Service Postal Université';
$actif = '/postal-univ/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Gestion des colis du service postal universitaire</p>
        </div>
        <a href="/postal-univ/reception" class="bouton bouton-principal">Recevoir un colis</a>
    </div>

    <div class="chiffres">
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Colis reçus</span>
            <div class="chiffre-valeur"><?= $stats["recus"] ?></div>
            <div class="chiffre-info">Total</div>
        </div>

        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">À transférer</span>
            <div class="chiffre-valeur"><?= $stats["a_transferer"] ?></div>
            <div class="chiffre-info">Vers l'IUT</div>
        </div>

        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Transférés</span>
            <div class="chiffre-valeur"><?= $stats["transferes"] ?></div>
            <div class="chiffre-info">Vers l'IUT</div>
        </div>

        <div class="chiffre chiffre-err">
            <span class="chiffre-titre">Non identifiés</span>
            <div class="chiffre-valeur"><?= $stats["non_identifies"] ?></div>
            <div class="chiffre-info">À traiter</div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Derniers colis reçus</h2>
        <a href="/postal-univ/colis" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($colis_recents)): ?>
        <div class="vide-cadre">Aucun colis reçu</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis_recents as $c): ?>
                <a class="carte-ligne" href="/postal-univ/colis">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"]) ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
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
