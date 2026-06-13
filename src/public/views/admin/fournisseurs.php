<?php
$titre = 'Fournisseurs – Admin';
$actif = '/admin/fournisseurs';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Gestion des fournisseurs</h1>
            <p class="page-subtitle">Consulter, modifier et supprimer les fournisseurs</p>
        </div>
        <a href="/admin/ajouter-fournisseur" class="bouton bouton-principal"><?= icone('plus', 14) ?>Ajouter un fournisseur</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="message message-ok">
            Fournisseur supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="message message-err">
            Suppression impossible : ce fournisseur est encore lié à des données (devis, bons de commande…). Supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <?php if (empty($fournisseurs)): ?>
        <div class="vide-cadre">Aucun fournisseur</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($fournisseurs as $f): ?>
                <div class="carte-ligne cliquable" onclick="location.href='/admin/modifier-fournisseur?id=<?= $f['id_fournisseur'] ?>'">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('fournisseurs', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= htmlspecialchars($f['nom']) ?></div>
                            <div class="cl-sous">Fournisseur</div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <div class="cl-champ"><span class="cl-cle">Contact</span><span class="cl-val"><?= htmlspecialchars($f['contact_nom'] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Email</span><span class="cl-val"><?= htmlspecialchars($f['contact_email'] ?: "—") ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Téléphone</span><span class="cl-val"><?= htmlspecialchars($f['contact_telephone'] ?: "—") ?></span></div>
                    </div>
                    <div class="cl-fin" onclick="event.stopPropagation()">
                        <form method="post" action="/admin/supprimer-fournisseur"
                              onsubmit="return confirm('Supprimer définitivement le fournisseur <?= htmlspecialchars($f['nom'], ENT_QUOTES) ?> ?');">
                            <input type="hidden" name="id_fournisseur" value="<?= $f['id_fournisseur'] ?>">
                            <button type="submit" class="btn-croix" title="Supprimer"><?= icone('croix', 16) ?></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
