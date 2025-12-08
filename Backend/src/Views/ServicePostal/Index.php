<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Postal UNIV</title>
    <link rel="stylesheet" href="Public/css/service-postal.css">
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <header class="header">
            <div>
                <h1>Tableau de bord - Service Postal UNIV</h1>
                <p class="subtitle">Réception et transfert des colis vers l'IUT</p>
            </div>
            <button class="btn-primary" onclick="alert('Fonctionnalité à venir')">
                + Recevoir un colis
            </button>
        </header>

        <!-- Cartes de statistiques -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon">📦</div>
                <div class="stat-content">
                    <h2><?= $colisRecusAujourdhui ?></h2>
                    <p>Colis reçus aujourd'hui</p>
                    <span class="stat-label">Nouvelles réceptions</span>
                </div>
            </div>

            <div class="stat-card purple">
                <div class="stat-icon">🚚</div>
                <div class="stat-content">
                    <h2><?= $colisTransferes ?></h2>
                    <p>Transférés à l'IUT</p>
                    <span class="stat-label">En cours de transfert</span>
                </div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon">📋</div>
                <div class="stat-content">
                    <h2><?= $colisEnAttente ?></h2>
                    <p>En attente</p>
                    <span class="stat-label">À traiter</span>
                </div>
            </div>
        </div>

        <!-- Section colis reçus à l'UNIV -->
        <section class="colis-section">
            <div class="section-header">
                <h2>Colis reçus à l'UNIV</h2>
                <p>Colis à identifier et transférer</p>
            </div>
            
            <div class="search-bar">
                <input type="text" id="searchBC" placeholder="🔍 Rechercher BC">
            </div>
            
            <table class="colis-table">
                <thead>
                    <tr>
                        <th>N° Suivi</th>
                        <th>BC lié</th>
                        <th>Destinataire</th>
                        <th>Bureau</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colisRecus)): ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                Aucun colis reçu à traiter
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colisRecus as $colis): ?>
                            <tr>
                                <td><?= htmlspecialchars($colis['numero_suivi'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($colis['numero_commande']) ?></td>
                                <td><?= htmlspecialchars($colis['destinataire'] ?? 'Non identifié') ?></td>
                                <td><?= htmlspecialchars($colis['departement'] ?? '-') ?></td>
                                <td><?= date('d/m/Y', strtotime($colis['date_reception'])) ?></td>
                                <td>
                                    <span class="badge-warning">
                                        <?= ucfirst(str_replace('_', ' ', $colis['statut'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-transfer" onclick="transfererColis(<?= $colis['id_colis'] ?>)">
                                        Transférer
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Section historique des transferts -->
        <section class="colis-section">
            <div class="section-header">
                <h2>Colis transférés à l'IUT</h2>
                <p>Historique des transferts</p>
            </div>
            
            <table class="colis-table">
                <thead>
                    <tr>
                        <th>N° Suivi</th>
                        <th>BC lié</th>
                        <th>Destinataire</th>
                        <th>Date transfert</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colisTransferesHist)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                Aucun colis transféré
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colisTransferesHist as $colis): ?>
                            <tr>
                                <td><?= htmlspecialchars($colis['numero_suivi'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($colis['numero_commande']) ?></td>
                                <td><?= htmlspecialchars($colis['destinataire'] ?? '-') ?></td>
                                <td>
                                    <?= $colis['date_retrait'] ? date('d/m/Y H:i', strtotime($colis['date_retrait'])) : '-' ?>
                                </td>
                                <td>
                                    <span class="badge-success">
                                        <?= ucfirst(str_replace('_', ' ', $colis['statut'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script src="Public/js/service-postal.js"></script>
</body>
</html>
