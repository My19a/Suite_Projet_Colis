<?php
$titre = 'Modifier département – Admin';
$actif = '/admin/departements';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Modifier le département</h1>
            <p class="page-subtitle">Mettre a jour les informations du département</p>
        </div>
    </div>

    <div class="bloc">
        <div class="formulaire">
            <form method="post" action="/admin/update-departement">
                <input type="hidden" name="id_departement" value="<?= $departement['id_departement'] ?>">

                <div class="champ">
                    <label class="etiquette">Nom du département</label>
                    <input type="text" name="nom" class="saisie" value="<?= htmlspecialchars($departement['nom']) ?>" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Budget total (EUR)</label>
                    <input type="number" name="budget_total" class="saisie" value="<?= $departement['budget_total'] ?>" step="0.01" required>
                </div>

                <div class="formulaire-boutons">
                    <button type="submit" class="bouton bouton-principal">Enregistrer</button>
                    <a class="bouton bouton-secondaire" href="/admin/departements">Annuler</a>
                </div>
            </form>

            <form method="post" action="/admin/supprimer-departement"
                  onsubmit="return confirm('Supprimer définitivement ce département ?');"
                  style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid #e5e7eb;">
                <input type="hidden" name="id_departement" value="<?= $departement['id_departement'] ?>">
                <button type="submit" class="bouton bouton-danger">Supprimer ce département</button>
            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
