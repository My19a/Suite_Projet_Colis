<?php
$titre = 'Recherche colis – Postal IUT';
$actif = '/postal/colis/recherche';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Recherche de colis</h1>
            <p class="page-subtitle">Trouvez un colis par numéro de suivi, BC, département ou ID</p>
        </div>
    </div>

    <form method="get" class="recherche">
        <input type="text" name="q" class="recherche-saisie" placeholder="N° suivi, BC, departement, ID colis..." value="<?= htmlspecialchars($_GET["q"] ?? "") ?>">
        <button type="submit" class="btn-loupe" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </form>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Resultats</h2>
    </div>
    <?php if (empty($resultats)): ?>
        <div class="vide-cadre">Aucun résultat</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($resultats as $c): ?>
                <a class="carte-ligne" href="/postal/colis/details?id=<?= $c["id_colis"] ?>">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('colis', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($c["numero_suivi"]) ?></div>
                            <div class="cl-sous">Colis #<?= $c["id_colis"] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Bon de commande</span><span class="cl-val"><?= htmlspecialchars($c["numero_commande"] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Département</span><span class="cl-val"><?= htmlspecialchars($c["departement"] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Date réception</span><span class="cl-val"><?= $c["date_reception"] ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="<?= badgeStatut($c["statut"]) ?>"><?= htmlspecialchars(joli($c["statut"])) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
