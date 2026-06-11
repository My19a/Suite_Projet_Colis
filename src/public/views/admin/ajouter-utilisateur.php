<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur – Admin</title>
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

    <div class="utilisateur-connecte">
        <div class="utilisateur-nom"><?= isset($_SESSION["user"]) ? htmlspecialchars($_SESSION["user"]->getFullName()) : "" ?></div>
        <div class="utilisateur-role"><?= isset($_SESSION["user"]) ? htmlspecialchars($_SESSION["user"]->getRole()) : "" ?></div>
    </div>
    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Ajouter un utilisateur</h1>
            <p class="page-subtitle">Créer un nouveau compte utilisateur</p>
        </div>
    </div>

    <div class="section">
        <div class="form-card">
            <form method="post" action="/admin/ajouter-utilisateur">

                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="fullName" class="form-input" placeholder="Ex: Jean Dupont" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="Ex: jean.dupont@univ.fr" required>
                </div>

                <div class="form-group">
                    <label class="form-label">UID CAS</label>
                    <input type="text" name="uid_cas" class="form-input" placeholder="Ex: jdupont" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        <option value="">-- Choisir un role --</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id_role'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Département</label>
                    <select name="departement_id" class="form-select">
                        <option value="">-- Aucun --</option>
                        <?php foreach ($departements as $d): ?>
                            <option value="<?= $d['id_departement'] ?>"><?= htmlspecialchars($d['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                    <a href="/admin/utilisateurs" class="btn btn-secondary">Annuler</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>
