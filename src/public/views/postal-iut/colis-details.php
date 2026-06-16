<?php
$titre = 'Détails du colis – Postal IUT';
require __DIR__ . '/../partials/header.php';

// Cycle de vie d'un colis
$etapes = [
    ['libelle' => "Reçu à l'université",  'icone' => 'reception'],
    ['libelle' => "Transféré à l'IUT",    'icone' => 'colis'],
    ['libelle' => "En attente de retrait", 'icone' => 'historique'],
    ['libelle' => "Remis au destinataire", 'icone' => 'valide'],
];
$ordreStatut = [
    'recu_universite' => 0, 'received' => 0,
    'transfere_iut'   => 1, 'transferred' => 1,
    'en_attente'      => 2, 'pending' => 2,
    'livre' => 3, 'retire' => 3, 'delivered' => 3, 'retrieved' => 3,
];
$statutColis  = strtolower(trim($colis['statut'] ?? ''));
$nonIdentifie = ($statutColis === 'non_identifie');
$idxActuel    = $ordreStatut[$statutColis] ?? 0;

// Date de chaque étape, déduite de l'historique (repli sur les dates du colis)
$datesEtape = [];
foreach ($historique as $h) {
    $a = strtolower($h['action'] ?? '');
    $d = $h['date_action'] ?? '';
    if ($d === '') continue;
    if (preg_match('/recep|re[çc]u/u', $a))      { $datesEtape[0] = $datesEtape[0] ?? $d; }
    elseif (preg_match('/transf/u', $a))          { $datesEtape[1] = $datesEtape[1] ?? $d; }
    elseif (preg_match('/attente/u', $a))         { $datesEtape[2] = $datesEtape[2] ?? $d; }
    elseif (preg_match('/remis|retir|livr/u', $a)){ $datesEtape[3] = $datesEtape[3] ?? $d; }
}
if (empty($datesEtape[0]) && !empty($colis['date_reception'])) $datesEtape[0] = $colis['date_reception'];
if (empty($datesEtape[3]) && !empty($colis['date_retrait']))   $datesEtape[3] = $colis['date_retrait'];

$jolieDate = fn($d, $fmt = 'd/m/Y') => $d ? date($fmt, strtotime($d)) : '—';
$libelleAction = function ($a) {
    $m = [
        'reception universite' => "Réception à l'université",
        'transfert iut'        => "Transfert à l'IUT",
        'remis au destinataire' => 'Remis au destinataire',
        'en attente de retrait' => 'En attente de retrait',
    ];
    return $m[strtolower(trim($a))] ?? $a;
};
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
        <span class="<?= badgeStatut($colis["statut"]) ?>"><?= htmlspecialchars(libelleStatut($colis["statut"])) ?></span>
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
    </div>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Suivi du colis</h2>
        </div>
        <?php if ($nonIdentifie): ?>
            <div class="suivi-alerte"><?= icone('alerte', 17) ?> Colis non identifié — en attente d'association à un bon de commande.</div>
        <?php else: ?>
            <div class="suivi">
                <?php foreach ($etapes as $i => $et): ?>
                    <?php $cls = $i < $idxActuel ? 'faite' : ($i === $idxActuel ? 'active' : 'afaire'); ?>
                    <div class="suivi-etape <?= $cls ?>">
                        <div class="suivi-pastille"><?= icone($i < $idxActuel ? 'valide' : $et['icone'], 16) ?></div>
                        <div class="suivi-libelle"><?= htmlspecialchars($et['libelle']) ?></div>
                        <div class="suivi-date"><?= !empty($datesEtape[$i]) ? htmlspecialchars($jolieDate($datesEtape[$i])) : '—' ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Informations</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <p style="color: var(--texte-leger); font-size: 12px; margin-bottom: 4px;">Date de réception</p>
                <p style="font-weight: 500; font-variant-numeric: tabular-nums;"><?= htmlspecialchars($jolieDate($colis["date_reception"])) ?></p>
            </div>
            <div>
                <p style="color: var(--texte-leger); font-size: 12px; margin-bottom: 4px;">Date de retrait</p>
                <p style="font-weight: 500; font-variant-numeric: tabular-nums;"><?= htmlspecialchars($jolieDate($colis["date_retrait"])) ?></p>
            </div>
            <div style="grid-column: 1 / -1;">
                <p style="color: var(--texte-leger); font-size: 12px; margin-bottom: 4px;">Commentaire</p>
                <p style="font-weight: 500;"><?= htmlspecialchars($colis["commentaire"] ?: "Aucun commentaire") ?></p>
            </div>
        </div>
    </div>

    <div class="bloc">
        <div class="bloc-entete">
            <h2 class="bloc-titre">Historique</h2>
        </div>
        <?php if (empty($historique)): ?>
            <?= etatVide('historique', 'Aucun historique', "Les événements liés à ce colis s'afficheront ici au fil de son parcours.") ?>
        <?php else: ?>
            <div class="fil">
                <?php foreach ($historique as $h): ?>
                    <div class="fil-evt">
                        <span class="fil-point"></span>
                        <div class="fil-action"><?= htmlspecialchars($libelleAction($h["action"])) ?></div>
                        <div class="fil-date"><?= !empty($h["date_action"]) ? htmlspecialchars($jolieDate($h["date_action"], 'd/m/Y H:i')) : '' ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
