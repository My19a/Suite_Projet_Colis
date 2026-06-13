<?php
$titre = 'Fournisseurs – Département';
$actif = '/departement/fournisseurs';
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
            <h1 class="page-title">Fournisseurs Autorises</h1>
            <p class="page-subtitle">Liste des fournisseurs validés par l'administration pour passer commande</p>
        </div>
    </div>

    <div class="message message-info">
        <span class="message-icone"><?= icone('info', 17) ?></span>
        <div class="message-corps">
            <strong>Fournisseurs validés uniquement</strong><br>
            Vous ne pouvez passer commande qu'auprès des fournisseurs listés ci-dessous. Ces partenaires ont ete validés par l'administration de l'IUT.
        </div>
    </div>

    <div class="bloc-entete">
        <h2 class="bloc-titre">Liste des fournisseurs (<?= isset($fournisseurs) ? count($fournisseurs) : 0 ?>)</h2>
        <span class="bloc-sous-titre">Fournisseurs autorisés pour vos commandes</span>
    </div>

    <?php if (empty($fournisseurs)): ?>
        <div class="vide-cadre">Aucun fournisseur disponible</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($fournisseurs as $f): ?>
                <div class="carte-ligne">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('fournisseurs', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($f['nom']) ?></div>
                            <div class="cl-sous">Fournisseur</div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Contact</span><span class="cl-val"><?= $f['contact_nom'] ? htmlspecialchars($f['contact_nom']) : '—' ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Email</span><span class="cl-val"><?= $f['contact_email'] ? htmlspecialchars($f['contact_email']) : '—' ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Téléphone</span><span class="cl-val"><?= $f['contact_telephone'] ? htmlspecialchars($f['contact_telephone']) : '—' ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="badge badge-valide">Autorisé</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
