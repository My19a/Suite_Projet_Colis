<?php
$titre = 'Mes bons de commande – Département';
$actif = '/departement/bons-commande';
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
            <h1 class="page-title">Mes Bons de Commande</h1>
            <p class="page-subtitle">Historique complet de vos commandes</p>
        </div>
    </div>

    <div class="search-container">
        <input type="text" class="search-input" placeholder="Rechercher par numéro, fournisseur ou statut..." id="searchCommandes" onkeyup="filterCommandes()">
        <button type="button" class="btn-loupe" onclick="filterCommandes()" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </div>

    <?php
    $totalCommandes = isset($bons) ? count($bons) : 0;
    $enAttente = 0;
    $signes = 0;
    $montantTotal = 0;
    if (isset($bons)) {
        foreach ($bons as $c) {
            if ($c['statut'] === 'en_preparation') $enAttente++;
            if ($c['statut'] === 'signe') $signes++;
            $montantTotal += $c['montant_estime'];
        }
    }
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total commandes</span>
            <div class="stat-value"><?= $totalCommandes ?></div>
        </div>
        <div class="stat-card stat-warning">
            <span class="stat-label">En attente</span>
            <div class="stat-value"><?= $enAttente ?></div>
        </div>
        <div class="stat-card stat-blue">
            <span class="stat-label">Signés</span>
            <div class="stat-value"><?= $signes ?></div>
        </div>
        <div class="stat-card stat-success">
            <span class="stat-label">Montant total</span>
            <div class="stat-value" style="font-size: 24px;"><?= number_format($montantTotal, 2, ',', ' ') ?> EUR</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Liste des bons de commande</h2>
            <span class="section-subtitle"><?= $totalCommandes ?> bon(s) de commande trouve(s)</span>
        </div>

        <div class="table-container">
            <table class="data-table" id="commandesTable">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bons)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">Aucun bon de commande trouve</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bons as $cmd): ?>
                            <tr class="commande-row">
                                <td><strong><?= htmlspecialchars($cmd['numero_commande']) ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($cmd['date_commande'])) ?></td>
                                <td><?= htmlspecialchars($cmd['fournisseur_nom']) ?></td>
                                <td><span class="montant"><?= number_format($cmd['montant_estime'], 2, ',', ' ') ?> EUR</span></td>
                                <td>
                                    <span class="badge badge-<?= $cmd['statut'] ?>">
                                        <?php
                                        $statutLabels = ['en_preparation' => 'En preparation', 'signe' => 'Signe', 'envoye' => 'Envoye', 'livre' => 'Livre', 'annule' => 'Annule'];
                                        echo $statutLabels[$cmd['statut']] ?? ucfirst(str_replace('_', ' ', $cmd['statut']));
                                        ?>
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
function filterCommandes() {
    const input = document.getElementById('searchCommandes');
    const filter = input.value.toLowerCase();
    const rows = document.getElementsByClassName('commande-row');
    for (let row of rows) {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
