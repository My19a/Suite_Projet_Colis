<?php
$titre = 'Modifier fournisseur – Admin';
$actif = '/admin/fournisseurs';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Modifier le fournisseur</h1>
            <p class="page-subtitle">Mettre a jour les informations du fournisseur</p>
        </div>
    </div>

    <div class="bloc">
        <div class="formulaire">
            <form method="post" action="/admin/update-fournisseur">
                <input type="hidden" name="id_fournisseur" value="<?= $fournisseur['id_fournisseur'] ?>">

                <div class="champ">
                    <label class="etiquette">Nom</label>
                    <input type="text" name="nom" class="saisie" value="<?= htmlspecialchars($fournisseur['nom']) ?>" required>
                </div>

                <div class="champ">
                    <label class="etiquette">Nom du contact</label>
                    <input type="text" name="contact_nom" class="saisie" value="<?= htmlspecialchars($fournisseur['contact_nom'] ?? '') ?>">
                </div>

                <div class="champ">
                    <label class="etiquette">Email</label>
                    <input type="email" name="contact_email" class="saisie" value="<?= htmlspecialchars($fournisseur['contact_email'] ?? '') ?>">
                </div>

                <div class="champ">
                    <label class="etiquette">Téléphone</label>
                    <input type="text" name="contact_telephone" class="saisie" value="<?= htmlspecialchars($fournisseur['contact_telephone'] ?? '') ?>">
                </div>

                <div class="formulaire-boutons">
                    <button type="submit" class="bouton bouton-principal">Enregistrer</button>
                    <a class="bouton bouton-secondaire" href="/admin/fournisseurs">Annuler</a>
                </div>
            </form>

            <form method="post" action="/admin/supprimer-fournisseur"
                  onsubmit="return confirm('Supprimer définitivement ce fournisseur ?');"
                  style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid #e5e7eb;">
                <input type="hidden" name="id_fournisseur" value="<?= $fournisseur['id_fournisseur'] ?>">
                <button type="submit" class="bouton bouton-danger">Supprimer ce fournisseur</button>
            </form>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
