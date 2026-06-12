<?php
$titre = 'Tous les devis – Admin';
$actif = '/admin/devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tous les devis</h1>
            <p class="page-subtitle">Vue globale de l'ensemble des devis du système</p>
        </div>
    </div>

    <?php if (!empty($stats)): ?>
    <div class="stats-grid">
        <?php foreach ($stats as $s): ?>
        <div class="stat-card">
            <div class="stat-value"><?= $s['total'] ?></div>
            <div class="stat-label"><?= ucfirst(str_replace('_', ' ', $s['statut'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="get" class="search-container">
        <input type="text" name="q" class="search-input" placeholder="Rechercher un devis…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn-loupe" title="Rechercher"><?= icone('recherche', 15) ?></button>
    </form>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Département</th>
                        <th>Fournisseur</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr><td colspan="7" class="empty-state">Aucun devis trouve</td></tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                        <tr>
                            <td>#<?= $d['id_devis'] ?></td>
                            <td><strong><?= htmlspecialchars($d['objet']) ?></strong></td>
                            <td><?= htmlspecialchars($d['departement'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($d['fournisseur'] ?? '—') ?></td>
                            <td><span class="montant"><?= number_format($d['montant_estime'], 2, ',', ' ') ?> EUR</span></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $d['statut'])) ?>"><?= ucfirst(str_replace('_', ' ', $d['statut'])) ?></span></td>
                            <td><?= $d['date_demande'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
