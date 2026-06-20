<?php
$titre = 'Préparer le bon de commande';
$actif = '/directeur/devis';
$numeroPropose = "BC-" . date("Y") . "-" . str_pad($devis["id_devis"], 3, "0", STR_PAD_LEFT);
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1 class="page-title">Préparer le bon de commande</h1>
        <p class="page-subtitle">Corriger les informations puis déclarer les colis attendus</p>
    </div>
</div>

<?php if (!empty($erreur)): ?>
    <div class="message message-err">
        <span class="message-corps"><?= htmlspecialchars($erreur) ?></span>
    </div>
<?php endif; ?>

<div class="bloc">
    <form method="post" action="/directeur/signer-devis?id=<?= (int) $devis["id_devis"] ?>" class="formulaire">
        <div class="grille-formulaire">
            <div class="champ">
                <label class="etiquette requis">Numéro du bon de commande</label>
                <input class="saisie" type="text" name="numero_commande" value="<?= htmlspecialchars($_POST["numero_commande"] ?? $numeroPropose) ?>" required>
            </div>

            <div class="champ">
                <label class="etiquette requis">Montant</label>
                <input class="saisie" type="number" name="montant_estime" min="0" step="0.01" value="<?= htmlspecialchars($_POST["montant_estime"] ?? $devis["montant_estime"]) ?>" required>
            </div>

            <div class="champ">
                <label class="etiquette">Livraison estimée</label>
                <input class="saisie" type="date" name="date_estimee_livraison" value="<?= htmlspecialchars($_POST["date_estimee_livraison"] ?? "") ?>">
            </div>
        </div>

        <div class="champ">
            <label class="etiquette requis">Objet de la demande</label>
            <input class="saisie" type="text" name="objet" value="<?= htmlspecialchars($_POST["objet"] ?? $devis["objet"]) ?>" required>
            <small class="aide-champ">
                Demandeur : <?= htmlspecialchars($devis["demandeur_nom"] ?? "—") ?> ·
                Département : <?= htmlspecialchars($devis["departement_nom"] ?? "—") ?> ·
                Fournisseur : <?= htmlspecialchars($devis["fournisseur_nom"] ?? "—") ?>
            </small>
        </div>

        <div class="bloc-entete" style="margin-top: 18px;">
            <h2 class="bloc-titre">Colis rattachés à cette commande</h2>
            <button type="button" class="bouton bouton-secondaire bouton-petit" onclick="ajouterLigneColis()">Ajouter un colis</button>
        </div>

        <div id="lignes-colis" class="liste" style="margin-top: 10px;">
            <div class="carte-ligne ligne-colis">
                <div class="cl-champs" style="width: 100%;">
                    <div class="champ">
                        <label class="etiquette requis">Numéro de suivi</label>
                        <input class="saisie" type="text" name="numero_suivi[]" placeholder="Ex: LP123456789FR" required>
                    </div>
                    <div class="champ">
                        <label class="etiquette">Description</label>
                        <input class="saisie" type="text" name="description[]" placeholder="Ex: Carton écrans 27 pouces">
                    </div>
                    <div class="champ">
                        <label class="etiquette requis">Qté</label>
                        <input class="saisie" type="number" name="quantite[]" min="1" value="1" required>
                    </div>
                </div>
                <div class="cl-fin">
                    <button type="button" class="bouton bouton-petit bouton-danger" onclick="retirerLigneColis(this)">Retirer</button>
                </div>
            </div>
        </div>

        <div class="formulaire-boutons">
            <a href="/directeur/devis" class="bouton bouton-secondaire">Annuler</a>
            <button type="submit" class="bouton bouton-principal">Valider et envoyer au responsable colis</button>
        </div>
    </form>
</div>

<script>
function ajouterLigneColis() {
    const conteneur = document.getElementById('lignes-colis');
    const modele = conteneur.querySelector('.ligne-colis');
    const clone = modele.cloneNode(true);
    clone.querySelectorAll('input').forEach((input) => {
        input.value = input.name === 'quantite[]' ? '1' : '';
    });
    conteneur.appendChild(clone);
}

function retirerLigneColis(bouton) {
    const lignes = document.querySelectorAll('.ligne-colis');
    if (lignes.length === 1) return;
    bouton.closest('.ligne-colis').remove();
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
