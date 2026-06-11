<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur – Admin</title>
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
            <h1 class="page-title">Modifier l'utilisateur</h1>
            <p class="page-subtitle">Mettre à jour les informations de l'utilisateur</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <form method="post" action="/admin/update-utilisateur">
                <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">

                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="fullName" class="form-input" value="<?= htmlspecialchars($utilisateur['fullName']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">UID CAS</label>
                    <input type="text" name="uid_cas" class="form-input" value="<?= htmlspecialchars($utilisateur['uid_cas']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id_role'] ?>" <?= $r['id_role'] == $utilisateur['role_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Département</label>
                    <select name="departement_id" class="form-select">
                        <option value="">— Aucun —</option>
                        <?php foreach ($departements as $d): ?>
                            <option value="<?= $d['id_departement'] ?>" <?= $d['id_departement'] == $utilisateur['departement_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="/admin/utilisateurs" class="btn btn-secondary">Annuler</a>
                </div>
            </form>

            <form method="post" action="/admin/supprimer-utilisateur"
                  onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');"
                  style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid #e5e7eb;">
                <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">
                <button type="submit" class="btn btn-danger">Supprimer cet utilisateur</button>
            </form>
        </div>
    </div>

</main>

</body>
</html>
