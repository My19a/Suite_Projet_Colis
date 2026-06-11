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
        <a href="/admin/ajouter-fournisseur" class="btn btn-primary"><?= icone('plus', 14) ?>Ajouter un fournisseur</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">
            Fournisseur supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="alert alert-error">
            Suppression impossible : ce fournisseur est encore lié à des données (devis, bons de commande…). Supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <div class="section">
        <h3 class="section-title">Liste des fournisseurs</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($fournisseurs)): ?>
                        <tr><td colspan="6" class="empty-state">Aucun fournisseur</td></tr>
                    <?php else: ?>
                        <?php foreach ($fournisseurs as $f): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($f['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($f['contact_nom'] ?: "—") ?></td>
                            <td><?= htmlspecialchars($f['contact_email'] ?: "—") ?></td>
                            <td><?= htmlspecialchars($f['contact_telephone'] ?: "—") ?></td>
                            <td>
                                <a class="btn btn-sm btn-secondary" href="/admin/modifier-fournisseur?id=<?= $f['id_fournisseur'] ?>">Modifier</a>
                            </td>
                            <td class="cellule-suppr">
                                <form method="post" action="/admin/supprimer-fournisseur"
                                      onsubmit="return confirm('Supprimer définitivement le fournisseur <?= htmlspecialchars($f['nom'], ENT_QUOTES) ?> ?');">
                                    <input type="hidden" name="id_fournisseur" value="<?= $f['id_fournisseur'] ?>">
                                    <button type="submit" class="btn-croix" title="Supprimer"><?= icone('croix', 14) ?></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
