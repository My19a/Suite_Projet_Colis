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
        <a href="/admin/ajouter-utilisateur" class="bouton bouton-principal"><?= icone('plus', 14) ?>Ajouter un utilisateur</a>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="message message-ok">
            Utilisateur enregistré avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="message message-ok">
            Utilisateur supprimé avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'fk'): ?>
        <div class="message message-err">
            Suppression impossible : cet utilisateur est encore lié à des données (devis, bons de commande, colis…). Réaffectez ou supprimez d'abord ces éléments.
        </div>
    <?php endif; ?>

    <div class="bloc">
        <div class="tableau-cadre">
            <table class="tableau tableau-aere">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>UID CAS</th>
                        <th>Rôle</th>
                        <th>Département</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($utilisateurs)): ?>
                        <tr><td colspan="6" class="vide">Aucun utilisateur</td></tr>
                    <?php else: ?>
                        <?php foreach ($utilisateurs as $u): ?>
                        <tr onclick="location.href='/admin/modifier-utilisateur?id=<?= $u["id_utilisateur"] ?>'" style="cursor:pointer;">
                            <td>
                                <div class="cellule-utilisateur">
                                    <span class="cellule-avatar"><?= icone('utilisateur', 16) ?></span>
                                    <strong><?= htmlspecialchars($u["fullName"]) ?></strong>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($u["email"]) ?></td>
                            <td><?= htmlspecialchars($u["uid_cas"]) ?></td>
                            <td><span class="badge"><?= htmlspecialchars(libelleRole($u["role"])) ?></span></td>
                            <td><?= htmlspecialchars($u["departement"] ?? "—") ?></td>
                            <td class="cellule-suppr" onclick="event.stopPropagation()">
                                <form method="post" action="/admin/supprimer-utilisateur"
                                      onsubmit="return confirm('Supprimer définitivement <?= htmlspecialchars($u["fullName"], ENT_QUOTES) ?> ?');">
                                    <input type="hidden" name="id_utilisateur" value="<?= $u["id_utilisateur"] ?>">
                                    <button type="submit" class="btn-croix" title="Supprimer"><?= icone('croix', 16) ?></button>
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
