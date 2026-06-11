<?php
$titre = 'Liste des colis – Postal Université';
$actif = '/postal-univ/colis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Liste des colis reçus</h1>
            <p class="page-subtitle">Tous les colis réceptionnés par l'université</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>N° bon de commande</th>
                        <th>Campus / IUT</th>
                        <th>Statut</th>
                        <th>Date réception</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr><td colspan="7" class="empty-state">Aucun colis</td></tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td>#<?= $c["id_colis"] ?></td>
                            <td><strong><?= htmlspecialchars($c["numero_suivi"] ?: "—") ?></strong></td>
                            <td><?= htmlspecialchars($c["numero_commande"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "Non identifie") ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= $c["statut"] ?></span></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td>
                                <?php if ($c["statut_id"] == 1): ?>
                                    <a class="btn btn-sm btn-primary" href="/postal-univ/transferer?id=<?= $c["id_colis"] ?>">Transferer vers IUT</a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
