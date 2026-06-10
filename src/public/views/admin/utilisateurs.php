<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs – Admin</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Administrateur</h2>
        <p>Gestion du système</p>
    </div>

    <nav class="menu">
        <a href="/admin/dashboard">Tableau de bord</a>
        <a class="actif" href="/admin/utilisateurs">Utilisateurs</a>
        <a href="/admin/departements">Départements</a>
        <a href="/admin/fournisseurs">Fournisseurs</a>
        <a href="/admin/devis">Tous les devis</a>
        <a href="/admin/colis">Tous les colis</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Gestion des utilisateurs</h1>
            <p class="page-subtitle">Modifier les roles et départements des utilisateurs</p>
        </div>
    </div>

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
                            <form method="post" action="/admin/update-utilisateur">
                                <td><strong><?= htmlspecialchars($u["fullName"]) ?></strong></td>
                                <td><?= htmlspecialchars($u["email"]) ?></td>
                                <td><?= htmlspecialchars($u["uid_cas"]) ?></td>
                                <td>
                                    <select name="role_id" class="form-select" style="min-width: 150px;">
                                        <?php foreach ($roles as $r): ?>
                                            <option value="<?= $r["id_role"] ?>" <?= $r["id_role"] == $u["role_id"] ? "selected" : "" ?>>
                                                <?= htmlspecialchars($r["libelle"]) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="departement_id" class="form-select" style="min-width: 150px;">
                                        <option value="">—</option>
                                        <?php foreach ($departements as $d): ?>
                                            <option value="<?= $d["id_departement"] ?>" <?= $d["id_departement"] == $u["departement_id"] ? "selected" : "" ?>>
                                                <?= htmlspecialchars($d["nom"]) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="id_utilisateur" value="<?= $u["id_utilisateur"] ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>
