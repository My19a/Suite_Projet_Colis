<?php
$titre = 'Colis reçus – Postal IUT';
$actif = '/postal/colis/recus';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis reçus a l'IUT</h1>
            <p class="page-subtitle">Colis transférés depuis l'université</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Département</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Aucun colis reçu</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td><a href="/postal/colis/details?id=<?= $c["id_colis"] ?>" class="btn-link">#<?= $c["id_colis"] ?></a></td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "Non identifie") ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= $c["statut"] ?></span></td>
                            <td>
                                <a class="btn btn-sm btn-success" href="/postal/colis/retirer?id=<?= $c["id_colis"] ?>">Retire</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
