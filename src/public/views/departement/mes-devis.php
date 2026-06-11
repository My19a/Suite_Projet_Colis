<?php
$titre = 'Mes devis – Département';
$actif = '/departement/mes-devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header-simple">
        <a href="/departement/dashboard" class="back-button-simple">
            <span class="back-arrow">&larr;</span>
            Retour
        </a>
    </div>

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Mes Devis</h1>
            <p class="page-subtitle">Historique complet de vos devis</p>
        </div>
        <button class="btn btn-primary" onclick="window.location.href='/departement/creer-devis'">Nouveau devis</button>
    </div>

    <div class="search-container">
        <span class="search-icon-text"><?= icone('recherche', 15) ?></span>
        <input type="text" class="search-input" placeholder="Rechercher par objet, fournisseur ou statut..." id="searchDevis" onkeyup="filterDevis()">
    </div>

    <?php
    $totalDevis = isset($devis) ? count($devis) : 0;
    $enAttente = 0;
    $valides = 0;
    $rejetes = 0;
    if (isset($devis)) {
        foreach ($devis as $d) {
            if ($d['statut'] === 'en_attente') $enAttente++;
            if (in_array($d['statut'], ['valide_finance', 'signe_directeur'])) $valides++;
            if ($d['statut'] === 'rejete_finance') $rejetes++;
        }
    }
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total devis</span>
            <div class="stat-value"><?= $totalDevis ?></div>
        </div>
        <div class="stat-card stat-warning">
            <span class="stat-label">En attente</span>
            <div class="stat-value"><?= $enAttente ?></div>
        </div>
        <div class="stat-card stat-success">
            <span class="stat-label">Validés</span>
            <div class="stat-value"><?= $valides ?></div>
        </div>
        <?php if ($rejetes > 0): ?>
        <div class="stat-card stat-danger">
            <span class="stat-label">Rejetés</span>
            <div class="stat-value"><?= $rejetes ?></div>
        </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Liste des devis</h2>
            <span class="section-subtitle"><?= $totalDevis ?> devis trouve(s)</span>
        </div>

        <div class="table-container">
            <table class="data-table" id="devisTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Objet</th>
                        <th>Fournisseur</th>
                        <th>Montant estime</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">Aucun devis trouve</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                            <tr class="devis-row">
                                <td><?= date('d/m/Y', strtotime($d['date_demande'])) ?></td>
                                <td><strong><?= htmlspecialchars($d['objet']) ?></strong></td>
                                <td><?= htmlspecialchars($d['fournisseur_nom']) ?></td>
                                <td><span class="montant"><?= number_format($d['montant_estime'], 2, ',', ' ') ?> EUR</span></td>
                                <td>
                                    <?php
                                    $statutLabels = [
                                        'en_attente' => 'En attente',
                                        'valide_finance' => 'Valide (Finance)',
                                        'rejete_finance' => 'Rejete (Finance)',
                                        'signe_directeur' => 'Signe (Directeur)'
                                    ];
                                    $statutClass = [
                                        'en_attente' => 'en_attente',
                                        'valide_finance' => 'valide',
                                        'rejete_finance' => 'refuse',
                                        'signe_directeur' => 'signe'
                                    ];
                                    ?>
                                    <span class="badge badge-<?= $statutClass[$d['statut']] ?? 'default' ?>">
                                        <?= $statutLabels[$d['statut']] ?? ucfirst(str_replace('_', ' ', $d['statut'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<script>
function filterDevis() {
    const input = document.getElementById('searchDevis');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('devisTable');
    const rows = table.getElementsByClassName('devis-row');

    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
