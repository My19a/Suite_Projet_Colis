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
        <button class="bouton bouton-principal" onclick="window.location.href='/tickets/nouveau'">
            <?= icone('plus', 14) ?>Signaler un probleme
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
    <div class="chiffres">
        <div class="chiffre chiffre-attn">
            <span class="chiffre-titre">Ouverts</span>
            <div class="chiffre-valeur"><?= $stats['ouvert'] ?></div>
            <div class="chiffre-info">En attente de traitement</div>
        </div>
        <div class="chiffre chiffre-info-c">
            <span class="chiffre-titre">En cours</span>
            <div class="chiffre-valeur"><?= $stats['en_cours'] ?></div>
            <div class="chiffre-info">Pris en charge</div>
        </div>
        <div class="chiffre chiffre-ok">
            <span class="chiffre-titre">Resolus</span>
            <div class="chiffre-valeur"><?= $stats['resolu'] ?></div>
            <div class="chiffre-info">Cloture</div>
        </div>
    </div>

    <div class="ticket-filtres">
        <a class="ticket-filtre <?= !$filtre ? 'actif' : '' ?>" href="/tickets">Tous</a>
        <a class="ticket-filtre <?= $filtre === 'ouvert' ? 'actif' : '' ?>" href="/tickets?statut=ouvert">Ouverts</a>
        <a class="ticket-filtre <?= $filtre === 'en_cours' ? 'actif' : '' ?>" href="/tickets?statut=en_cours">En cours</a>
        <a class="ticket-filtre <?= $filtre === 'resolu' ? 'actif' : '' ?>" href="/tickets?statut=resolu">Resolus</a>
    </div>
    <?php endif; ?>

    <?php if (empty($tickets)): ?>
        <div class="vide-cadre">Aucun ticket pour le moment.</div>
    <?php else: ?>
        <div class="liste">
            <?php foreach ($tickets as $t): ?>
                <a class="carte-ligne" href="/tickets/<?= (int) $t['id_ticket'] ?>">
                    <div class="cl-tete">
                        <div class="cl-icone"><?= icone('assistance', 19) ?></div>
                        <div>
                            <div class="cl-titre"><?= e($t['sujet']) ?></div>
                            <div class="cl-sous">Ticket #<?= (int) $t['id_ticket'] ?></div>
                        </div>
                    </div>
                    <div class="cl-champs">
                        <?php if ($estSupport): ?>
                            <div class="cl-champ"><span class="cl-cle">Demandeur</span><span class="cl-val"><?= e($t['createur_nom'] ?? '') ?></span></div>
                        <?php endif; ?>
                        <div class="cl-champ"><span class="cl-cle">Messages</span><span class="cl-val"><?= (int) ($t['nb_messages'] ?? 0) ?></span></div>
                        <div class="cl-champ"><span class="cl-cle">Mise à jour</span><span class="cl-val"><?= date('d/m/Y H:i', strtotime($t['date_maj'])) ?></span></div>
                    </div>
                    <div class="cl-fin">
                        <span class="badge badge-priorite-<?= e($t['priorite']) ?>"><?= e(joli($t['priorite'])) ?></span>
                        <span class="badge badge-<?= e($t['statut']) ?>"><?= e(joli($t['statut'])) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
