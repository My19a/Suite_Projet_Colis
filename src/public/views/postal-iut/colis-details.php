<?php
$titre = 'Détails du colis – Postal IUT';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header-simple">
        <a href="/postal/colis/recus" class="lien-retour">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Détails du colis #<?= $colis["id_colis"] ?></h1>
        </div>
    </div>

    <div class="chiffres" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="chiffre">
            <span class="chiffre-titre">N° suivi</span>
            <div class="chiffre-valeur" style="font-size: 18px;"><?= htmlspecialchars($colis["numero_suivi"] ?: "—") ?></div>
        </div>
        <div class="chiffre">
            <span class="chiffre-titre">Bon de commande</span>
            <div class="chiffre-valeur" style="font-size: 18px;"><?= htmlspecialchars($colis["numero_commande"] ?: "—") ?></div>
        </div>
        <div class="chiffre">
            <span class="chiffre-titre">Département</span>
            <div class="chiffre-valeur" style="font-size: 18px;"><?= htmlspecialchars($colis["departement"] ?: "Non identifié") ?></div>
        </div>
        <div class="chiffre">
            <span class="chiffre-titre">Statut</span>
            <div style="margin-top: 8px;">
                <span class="<?= badgeStatut($colis["statut"]) ?>"><?= htmlspecialchars(libelleStatut($colis["statut"])) ?></span>
            </div>
        </div>
    </div>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Informations</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Date réception</p>
                <p style="font-weight: 600;"><?= $colis["date_reception"] ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Date retrait</p>
                <p style="font-weight: 600;"><?= $colis["date_retrait"] ?: "—" ?></p>
            </div>
            <div style="grid-column: 1 / -1;">
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 4px;">Commentaire</p>
                <p style="font-weight: 500;"><?= htmlspecialchars($colis["commentaire"] ?: "Aucun commentaire") ?></p>
            </div>
        </div>
    </div>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Historique</h2>
        </div>
        <div class="tableau-cadre">
            <table class="tableau">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)): ?>
                        <tr>
                            <td colspan="2" class="vide">Aucun historique disponible</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($historique as $h): ?>
                        <tr>
                            <td><?= $h["date_action"] ?></td>
                            <td><span class="badge"><?= htmlspecialchars($h["action"]) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
