<?php
$titre = 'Utilisateurs – Admin';
$actif = '/admin/utilisateurs';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Gestion des utilisateurs</h1>
            <p class="page-subtitle">Consulter, modifier et supprimer les utilisateurs</p>
        </div>
        <a href="/admin/ajouter-utilisateur" class="btn btn-primary">Ajouter un utilisateur</a>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success">
            Utilisateur enregistré avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">
            Utilisateur supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="alert alert-error">
            Suppression impossible : cet utilisateur est encore lié à des données (devis, bons de commande, colis…). Réaffectez ou supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>UID CAS</th>
                        <th>Role</th>
                        <th>Département</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($utilisateurs)): ?>
                        <tr><td colspan="6" class="empty-state">Aucun utilisateur</td></tr>
                    <?php else: ?>
                        <?php foreach ($utilisateurs as $u): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($u["fullName"]) ?></strong></td>
                            <td><?= htmlspecialchars($u["email"]) ?></td>
                            <td><?= htmlspecialchars($u["uid_cas"]) ?></td>
                            <td><?= htmlspecialchars($u["role"]) ?></td>
                            <td><?= htmlspecialchars($u["departement"] ?? "—") ?></td>
                            <td>
                                <a href="/admin/modifier-utilisateur?id=<?= $u["id_utilisateur"] ?>" class="btn btn-sm btn-primary">Modifier</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
