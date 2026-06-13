<?php
$titre = 'Modifier un utilisateur – Admin';
$actif = '/admin/utilisateurs';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Modifier l'utilisateur</h1>
            <p class="page-subtitle">Mettre à jour les informations de l'utilisateur</p>
        </div>
    </div>

    <div class="bloc">
        <div class="formulaire">
            <form method="post" action="/admin/update-utilisateur">
                <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">

                <div class="champ">
                    <label class="etiquette">Nom complet</label>
                    <input type="text" name="fullName" class="saisie" value="<?= htmlspecialchars($utilisateur['fullName']) ?>" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Email</label>
                    <input type="email" name="email" class="saisie" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
                </div>

                <div class="champ">
                    <label class="etiquette">UID CAS</label>
                    <input type="text" name="uid_cas" class="saisie" value="<?= htmlspecialchars($utilisateur['uid_cas']) ?>" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Role</label>
                    <select name="role_id" class="liste-deroulante" required>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id_role'] ?>" <?= $r['id_role'] == $utilisateur['role_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="champ">
                    <label class="etiquette">Département</label>
                    <select name="departement_id" class="liste-deroulante">
                        <option value="">— Aucun —</option>
                        <?php foreach ($departements as $d): ?>
                            <option value="<?= $d['id_departement'] ?>" <?= $d['id_departement'] == $utilisateur['departement_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="formulaire-boutons">
                    <button type="submit" class="bouton bouton-principal">Enregistrer</button>
                    <a href="/admin/utilisateurs" class="bouton bouton-secondaire">Annuler</a>
                </div>
            </form>

            <form method="post" action="/admin/supprimer-utilisateur"
                  onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');"
                  style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid #e5e7eb;">
                <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">
                <button type="submit" class="bouton bouton-danger">Supprimer cet utilisateur</button>
            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
