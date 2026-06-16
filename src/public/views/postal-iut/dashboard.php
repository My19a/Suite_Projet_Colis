<?php
$titre = 'Dashboard – Service Postal IUT';
$actif = '/postal/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue d'ensemble des colis du service postal IUT</p>
        </div>
    </div>

    <div class="chiffres">
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">Reçus à l'IUT</span>
            <div class="chiffre-valeur"><?= $stats["recus"] ?></div>
            <div class="chiffre-info">Colis reçus</div>
        </div>

        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">En attente</span>
            <div class="chiffre-valeur"><?= $stats["en_attente"] ?></div>
            <div class="chiffre-info">À retirer</div>
        </div>

        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Retirés</span>
            <div class="chiffre-valeur"><?= $stats["retires"] ?></div>
            <div class="chiffre-info">Colis livrés</div>
        </div>

        <div class="chiffre chiffre-err">
            <span class="chiffre-titre">Non identifiés</span>
            <div class="chiffre-valeur"><?= $stats["non_identifies"] ?></div>
            <div class="chiffre-info">À traiter</div>
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Derniers colis reçus</h2>
        <a href="/postal/colis/recus" class="lien-action">Voir tout</a>
    </div>

    <?php if (empty($colis)): ?>
        <?= etatVide('colis', 'Aucun colis', 'Les colis reçus s\'afficheront ici.') ?>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($colis as $c): ?>
                <a class="carte-ligne" href="/postal/colis/details?id=<?= $c["id_colis"] ?>">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"]) ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "—") ?></span></div>
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
