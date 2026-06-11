<?php
$titre = 'Ajouter un utilisateur – Admin';
$actif = '/admin/utilisateurs';
require __DIR__ . '/../partials/header.php';
?>

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

<?php require __DIR__ . '/../partials/footer.php'; ?>
