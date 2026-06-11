<?php
$titre = $estSupport ? 'Tickets - Assistance' : 'Mes tickets';
$actif = '/tickets';
$feuillesDeStyle = ['/assets/css/style-tickets.css'];
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title"><?= $estSupport ? 'Tickets d\'assistance' : 'Mes tickets' ?></h1>
            <p class="page-subtitle">
                <?= $estSupport ? 'Suivez et traitez les demandes des utilisateurs' : 'Vos demandes d\'assistance et leur suivi' ?>
            </p>
        </div>
        <button class="btn btn-primary" onclick="window.location.href='/tickets/nouveau'">
            Signaler un probleme
        </button>
    </div>

    <?php if (!empty($notifications)): ?>
    <div class="ticket-notifs">
        <span class="ticket-notifs-titre"><?= count($notifications) ?> nouvelle(s) reponse(s)</span>
        <ul>
            <?php foreach ($notifications as $n): ?>
                <li><?= e($n['message_notification']) ?>
                    <span class="ticket-notifs-date"><?= date('d/m/Y H:i', strtotime($n['date_envoi'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($estSupport && $stats !== null): ?>
    <div class="stats-grid">
        <div class="stat-card stat-warning">
            <span class="stat-label">Ouverts</span>
            <div class="stat-value"><?= $stats['ouvert'] ?></div>
            <div class="stat-description">En attente de traitement</div>
        </div>
        <div class="stat-card stat-blue">
            <span class="stat-label">En cours</span>
            <div class="stat-value"><?= $stats['en_cours'] ?></div>
            <div class="stat-description">Pris en charge</div>
        </div>
        <div class="stat-card stat-success">
            <span class="stat-label">Resolus</span>
            <div class="stat-value"><?= $stats['resolu'] ?></div>
            <div class="stat-description">Cloture</div>
        </div>
    </div>

    <div class="ticket-filtres">
        <a class="ticket-filtre <?= !$filtre ? 'actif' : '' ?>" href="/tickets">Tous</a>
        <a class="ticket-filtre <?= $filtre === 'ouvert' ? 'actif' : '' ?>" href="/tickets?statut=ouvert">Ouverts</a>
        <a class="ticket-filtre <?= $filtre === 'en_cours' ? 'actif' : '' ?>" href="/tickets?statut=en_cours">En cours</a>
        <a class="ticket-filtre <?= $filtre === 'resolu' ? 'actif' : '' ?>" href="/tickets?statut=resolu">Resolus</a>
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sujet</th>
                        <?php if ($estSupport): ?><th>Demandeur</th><?php endif; ?>
                        <th>Priorite</th>
                        <th>Statut</th>
                        <th>Messages</th>
                        <th>Mise a jour</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="<?= $estSupport ? 8 : 7 ?>" class="empty-state">
                                Aucun ticket pour le moment.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $t): ?>
                            <tr onclick="window.location.href='/tickets/<?= (int) $t['id_ticket'] ?>'" style="cursor:pointer;">
                                <td>#<?= (int) $t['id_ticket'] ?></td>
                                <td><?= e($t['sujet']) ?></td>
                                <?php if ($estSupport): ?>
                                    <td><?= e($t['createur_nom'] ?? '') ?></td>
                                <?php endif; ?>
                                <td>
                                    <span class="badge badge-priorite-<?= e($t['priorite']) ?>">
                                        <?= ucfirst(e($t['priorite'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= e($t['statut']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', e($t['statut']))) ?>
                                    </span>
                                </td>
                                <td><?= (int) ($t['nb_messages'] ?? 0) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($t['date_maj'])) ?></td>
                                <td><a class="btn-link" href="/tickets/<?= (int) $t['id_ticket'] ?>">Ouvrir</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
