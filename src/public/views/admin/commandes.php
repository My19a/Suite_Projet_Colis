<?php
$titre = 'Bons de commande – Admin';
$actif = '/admin/commandes';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Bons de commande</h1>
            <p class="page-subtitle">Liste de tous les bons de commande</p>
        </div>
    </div>

    <div class="section">
        <div class="stats-grid">
            <?php foreach ($stats as $statut => $count): ?>
            <div class="stat-card">
                <div class="stat-value"><?= $count ?></div>
                <div class="stat-label"><?= ucfirst(str_replace('_', ' ', $statut)) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <form method="get" class="search-container">
        <input type="text" name="q" class="search-input" placeholder="Rechercher par numéro…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn-loupe" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </form>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Département</th>
                        <th>Fournisseur</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($commandes)): ?>
                        <tr><td colspan="6" class="empty-state">Aucune commande</td></tr>
                    <?php else: ?>
                        <?php foreach ($commandes as $c): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($c['numero_commande']) ?></strong></td>
                            <td><?= htmlspecialchars($c['departement'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($c['fournisseur'] ?? '-') ?></td>
                            <td><?= number_format($c['montant_estime'] ?? 0, 2, ',', ' ') ?> EUR</td>
                            <td><span class="badge badge-<?= $c['statut'] ?>"><?= $c['statut'] ?></span></td>
                            <td><?= $c['date_commande'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
