<?php
$titre = 'Recherche colis – Postal IUT';
$actif = '/postal/colis/recherche';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Recherche de colis</h1>
            <p class="page-subtitle">Trouvez un colis par numéro de suivi, BC, département ou ID</p>
        </div>
    </div>

    <form method="get" class="search-form">
        <div class="search-container">
            <span class="search-icon-text"><?= icone('recherche', 15) ?></span>
            <input type="text" name="q" class="search-input" placeholder="N° suivi, BC, departement, ID colis..." value="<?= htmlspecialchars($_GET["q"] ?? "") ?>">
        </div>
        <button type="submit" class="btn btn-secondary">Rechercher</button>
    </form>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Resultats</h2>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Bon de commande</th>
                        <th>Département</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resultats)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Aucun résultat</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($resultats as $c): ?>
                        <tr>
                            <td>
                                <a href="/postal/colis/details?id=<?= $c["id_colis"] ?>" class="btn-link">#<?= $c["id_colis"] ?></a>
                            </td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_commande"] ?: "—") ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "—") ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= $c["statut"] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
