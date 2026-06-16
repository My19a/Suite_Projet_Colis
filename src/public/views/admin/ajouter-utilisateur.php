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

    <div class="bloc">
        <div class="formulaire">
            <form method="post" action="/admin/ajouter-utilisateur">

                <div class="champ">
                    <label class="etiquette">Nom complet</label>
                    <input type="text" name="fullName" class="saisie" placeholder="Ex: Jean Dupont" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Email</label>
                    <input type="email" name="email" class="saisie" placeholder="Ex: jean.dupont@univ.fr" required>
                </div>

                <div class="champ">
                    <label class="etiquette">UID CAS</label>
                    <input type="text" name="uid_cas" class="saisie" placeholder="Ex: jdupont" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Rôle</label>
                    <select name="role_id" class="liste-deroulante" required>
                        <option value="">-- Choisir un rôle --</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id_role'] ?>"><?= htmlspecialchars(libelleRole($r['libelle'])) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="champ">
                    <label class="etiquette">Département</label>
                    <select name="departement_id" class="liste-deroulante">
                        <option value="">-- Aucun --</option>
                        <?php foreach ($departements as $d): ?>
                            <option value="<?= $d['id_departement'] ?>"><?= htmlspecialchars($d['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="formulaire-boutons">
                    <button type="submit" class="bouton bouton-principal">Créer l'utilisateur</button>
                    <a href="/admin/utilisateurs" class="bouton bouton-secondaire">Annuler</a>
                </div>

            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
