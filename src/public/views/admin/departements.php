<?php
$titre = 'Départements – Admin';
$actif = '/admin/departements';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Gestion des départements</h1>
            <p class="page-subtitle">Consulter, modifier et supprimer les départements</p>
        </div>
        <a href="/admin/ajouter-departement" class="btn btn-primary">Ajouter un département</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">
            Département supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="alert alert-error">
            Suppression impossible : ce département est encore lié à des données (utilisateurs, devis, bons de commande…). Réaffectez ou supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Budget total</th>
                        <th>Budget utilisé</th>
                        <th>Budget restant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($departements)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun département</td></tr>
                    <?php else: ?>
                        <?php foreach ($departements as $d): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($d['nom']) ?></strong></td>
                            <td><?= number_format($d['budget_total'], 2, ',', ' ') ?> EUR</td>
                            <td><?= number_format($d['budget_utilise'], 2, ',', ' ') ?> EUR</td>
                            <td><span class="montant"><?= number_format($d['budget_total'] - $d['budget_utilise'], 2, ',', ' ') ?> EUR</span></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/admin/modifier-departement?id=<?= $d['id_departement'] ?>">Modifier</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
